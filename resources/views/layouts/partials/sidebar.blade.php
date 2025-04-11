<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/logo.png')" :logo-alt="asset('images/logo/logo.png')">
    
    <!--Sidebar Menu Items-->
    <!--dashboard-->
    <x-maz-sidebar-item name="Dashboard" :link="route('dashboard')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <!--pekerjaan-->
    <x-maz-sidebar-item name="Pekerjaan" :link="route('pages.pekerjaan')" icon="bi bi-briefcase-fill"></x-maz-sidebar-item>
    <!--keuangan-->
    <x-maz-sidebar-item name="Keuangan" icon="bi bi-currency-dollar">
        <x-maz-sidebar-sub-item name="Monev-Keuangan" :link="route('pages.keuangan.monev-keuangan')" icon="bi bi-graph-up"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Data-Keuangan" :link="route('pages.keuangan.data-keuangan')" icon="bi bi-file-earmark-spreadsheet"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    <!--Program-->
    <x-maz-sidebar-item name="Program" :link="route('program')" icon="bi bi-file-earmark-text"></x-maz-sidebar-item>
    <!--Jadwal Program-->
    <x-maz-sidebar-item name="Jadwal-Program" :link="route('pages.jadwal-program')" icon="bi bi-calendar"></x-maz-sidebar-item>
     <!--pengguna-->
     <x-maz-sidebar-item name="Pengguna" :link="route('pages.pengguna')" icon="bi bi-person-fill"></x-maz-sidebar-item>

</x-maz-sidebar>