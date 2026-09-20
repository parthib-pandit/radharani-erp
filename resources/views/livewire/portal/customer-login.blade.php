<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#FAF8F4;font-family:'Public Sans',sans-serif;">
    <div style="width:100%;max-width:360px;background:#fff;border:1px solid #E7E0D4;border-radius:14px;padding:32px;">
        <div style="font-family:'Newsreader',Georgia,serif;font-size:22px;text-align:center;margin-bottom:4px;">Radharani Jewellery</div>
        <div style="font-size:12px;color:#8B7F6F;text-align:center;margin-bottom:24px;">Customer Portal</div>

        <form wire:submit="login">
            <label style="display:block;font-size:11.5px;color:#8B7F6F;margin-bottom:4px;">Phone Number</label>
            <input type="text" wire:model="phone" class="rj-input" style="width:100%;margin-bottom:14px;padding:10px 12px;border:1px solid #E7E0D4;border-radius:8px;">
            @error('phone') <div style="color:#B04A3C;font-size:11.5px;margin:-8px 0 12px;">{{ $message }}</div> @enderror

            <label style="display:block;font-size:11.5px;color:#8B7F6F;margin-bottom:4px;">Password</label>
            <input type="password" wire:model="password" style="width:100%;margin-bottom:20px;padding:10px 12px;border:1px solid #E7E0D4;border-radius:8px;box-sizing:border-box;">

            <button type="submit" style="width:100%;background:#A9772F;color:#FFF7EC;font-weight:700;padding:12px;border:none;border-radius:8px;cursor:pointer;">Log In</button>
        </form>

        <div style="font-size:11.5px;color:#8B7F6F;text-align:center;margin-top:16px;">
            First time here? Ask at the counter to set up portal access.
        </div>
    </div>
</div>
