<?php
// Detect if we are inside the 'modules' folder or at the root
$current_dir = basename(getcwd());
$path_prefix = ($current_dir == 'modules') ? '../' : '';
$module_prefix = ($current_dir == 'modules') ? '' : 'modules/';
?>

<aside class="w-64 bg-slate-950 border-r border-slate-800 min-h-screen p-6 hidden lg:block">
    <div class="flex items-center gap-3 mb-10">
        <div class="w-8 h-8 bg-emerald-500 rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
        <h1 class="text-xl font-bold text-white tracking-tight">AgroNexus</h1>
    </div>
    
    <nav class="space-y-2 text-sm text-slate-400">
        <p class="text-slate-500 font-bold uppercase text-[10px] mb-4 tracking-widest">Intelligence Loop</p>
        
        <a href="<?php echo $path_prefix; ?>index.php" class="block p-3 hover:text-white hover:bg-slate-900 rounded-xl transition">
            Overview Dashboard
        </a>

        <a href="<?php echo $module_prefix; ?>macro_econ.php" class="block p-3 hover:text-white hover:bg-slate-900 rounded-xl transition">
            Macro Forecasting
        </a>
        
        <a href="<?php echo $module_prefix; ?>remote_sensing.php" class="block p-3 hover:text-white hover:bg-slate-900 rounded-xl transition">
            Remote Sensing
        </a>
        
        <a href="<?php echo $module_prefix; ?>risk_analysis.php" class="block p-3 hover:text-white hover:bg-slate-900 rounded-xl transition">
            Risk Simulator
        </a>
        
        <a href="<?php echo $module_prefix; ?>field_health.php" class="block p-3 hover:text-white hover:bg-slate-900 rounded-xl transition">
            Field Diagnostics
        </a>
    </nav>
</aside>