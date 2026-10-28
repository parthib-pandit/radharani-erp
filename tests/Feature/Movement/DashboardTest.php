<?php

namespace Tests\Feature\Movement;

use App\Livewire\Dashboard;
use App\Livewire\Pricing\DailyRateEntry;
use App\Livewire\Pricing\PriceSimulator;
use App\Models\Movement\RateLog;
use App\Models\Pricing\PricingRule;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 28 October: the dashboard is the greeting and the rates (updated right there by whoever can set them), five tiles like a
 * payment app's home screen, and the price simulator below them, with the making charge slider.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=DashboardTest
 */
class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->owner = User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.');
        $this->actingAs($this->owner);
        RateLog::create(['metal' => 'gold', 'purity' => '9K', 'rate' => 1000, 'source' => 'manual', 'updated_by' => $this->owner->id]);
        PricingRule::whereIn('kind', ['making', 'additional', 'discount', 'hallmark'])->update(['active' => false]);
        app(\App\Services\PricingService::class)->forgetRules();
    }

    private function userWith(string $role): User
    {
        return User::role($role)->where('is_active', true)->first() ?? $this->markTestSkipped("Needs an active {$role} user.");
    }

    public function test_the_hero_is_the_greeting_and_the_rates_and_the_owner_updates_them_in_place(): void
    {
        $c = Livewire::test(Dashboard::class);
        $c->assertSee("Today's rates", false)->assertSee('Update rates')->assertDontSee('See the list')->assertDontSee('need your attention');
        $this->assertMatchesRegularExpression('/Good (morning|afternoon|evening)/', $c->viewData('greeting'));

        // the same entry as Pricing & Rates, inside the page (no page heading of its own)
        $c->assertSeeLivewire(DailyRateEntry::class);
        Livewire::test(DailyRateEntry::class, ['embedded' => true])->assertDontSee('Daily Rate Entry')->assertSee("Today's rates", false)->assertSee('Save');
        Livewire::test(DailyRateEntry::class)->assertSee('Daily Rate Entry');
    }

    public function test_saving_the_rates_redraws_the_hero(): void
    {
        $gold = RateLog::headline('gold');
        $key = DailyRateEntry::key($gold->purity);

        Livewire::test(DailyRateEntry::class, ['embedded' => true])->set("rates.gold.{$key}", '98765')->call('save')->assertDispatched('rates-updated');

        $hero = Livewire::test(Dashboard::class)->dispatch('rates-updated')->viewData('rates');
        $this->assertEquals(98765, $hero->firstWhere('metal', 'gold')['rate']);
    }

    public function test_staff_who_can_not_set_the_rates_see_them_without_the_update(): void
    {
        $staff = $this->userWith('counter_staff');
        $this->assertFalse($staff->can('rate.update'));

        Livewire::actingAs($staff)->test(Dashboard::class)->assertSee("Today's rates", false)->assertDontSee('Update rates')->assertDontSeeLivewire(DailyRateEntry::class);
    }

    public function test_there_are_five_tiles_and_only_those_a_person_may_open(): void
    {
        $owner = Livewire::test(Dashboard::class);
        $this->assertSame(['New sale', 'New exchange', 'Scan a tag', 'Vault ↔ Counter', 'Monthly scheme'], collect($owner->viewData('actions'))->pluck('label')->all());
        $owner->assertSee('Monthly scheme');
        $this->assertSame(route('installments.monthly-status'), collect($owner->viewData('actions'))->firstWhere('label', 'Monthly scheme')['href']);

        $handler = $this->userWith('karigar_handler');
        $labels = collect(Livewire::actingAs($handler)->test(Dashboard::class)->viewData('actions'))->pluck('label')->all();
        $this->assertSame(['Scan a tag', 'Vault ↔ Counter'], $labels, 'a karigar handler can not sell, exchange or open schemes');
    }

    public function test_the_price_simulator_sits_below_the_tiles_for_those_who_price_pieces(): void
    {
        $c = Livewire::test(Dashboard::class)->assertSeeLivewire(PriceSimulator::class)->assertSee('Price simulator')->assertSee('Add New Sale');
        $this->assertNotSame(false, strpos($c->html(), 'Quick actions'));
        $html = $c->html();
        $this->assertLessThan(strpos($html, 'Price simulator'), strpos($html, 'Quick actions'), 'the tiles come first');

        $counter = $this->userWith('counter_staff');
        Livewire::actingAs($counter)->test(Dashboard::class)->assertSeeLivewire(PriceSimulator::class);

        $handler = $this->userWith('karigar_handler');
        Livewire::actingAs($handler)->test(Dashboard::class)->assertDontSeeLivewire(PriceSimulator::class);
    }

    public function test_the_simulator_page_has_add_new_sale_and_opens_for_sales_staff(): void
    {
        $this->get(route('pricing.simulator'))->assertOk()->assertSee('Add New Sale')->assertSee(route('sales.new'), false);

        $counter = $this->userWith('counter_staff');
        $this->actingAs($counter)->get(route('pricing.simulator'))->assertOk();
        $this->actingAs($this->userWith('karigar_handler'))->get(route('pricing.simulator'))->assertForbidden();
    }

    public function test_the_simulator_has_the_making_slider_that_bargains_down_to_the_floor(): void
    {
        $c = Livewire::test(PriceSimulator::class)->set('metal', 'gold')->set('purity', '9K')->set('weight', '10')->set('making_type', 'percentage')->set('making_value', '18');
        $r = $c->viewData('result');
        $this->assertEquals(1800, $r['making']);
        $this->assertEquals(10, $r['making_min_rate']);
        $c->assertSee('Bargain the making charge');

        $c->call('setBargain', '15');
        $this->assertEquals(1500, $c->viewData('result')['making']);
        $c->assertSee('Making charge reduced by ₹300.00');
        $this->assertSame('0.25', (string) 0.25);
        $this->assertStringContainsString('step="0.25"', $c->html());

        $c->call('setBargain', '2');
        $this->assertEquals(1000, $c->viewData('result')['making'], 'never under 10%');

        // a different making setting is a different pricing rate: the bargain starts again
        $c->set('making_value', '12');
        $this->assertSame('', $c->get('bargain'));
        $this->assertEquals(1200, $c->viewData('result')['making']);
    }

    public function test_the_embedded_simulator_has_no_page_heading_of_its_own(): void
    {
        Livewire::test(PriceSimulator::class, ['embedded' => true])->assertSee('Price simulator')->assertDontSee('Pricing &amp; Rates', false);
    }
}
