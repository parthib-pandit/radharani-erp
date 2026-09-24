// Alpine is bundled and started by Livewire v3 itself (window.Alpine is set
// once Livewire's own script runs) — importing/starting a second copy here
// caused "Detected multiple instances of Alpine running" and broke
// wire:model reactivity on any element that also had x-data. Don't
// reintroduce a manual Alpine.start() here.
