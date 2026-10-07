<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('dua:info',function(){ $this->info('Noor / Dua Daily is ready. Default login: admin@noor.local / NoorDaily!2026'); })->purpose('Show default Dua Daily login details');
