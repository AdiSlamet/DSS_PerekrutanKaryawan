<div class="navigation">
    <ul>
        <li>
            <a href="/home">
                <span class="icon">
                    <ion-icon name="business-outline"></ion-icon>
                </span>
                <span class="title">M-Coffie</span>
            </a>
        </li>

        <li class="{{ Request::is('home') || Request::is('/') ? 'active-menu' : '' }}">
            <a href="/dashboard">
                <span class="icon">
                    <ion-icon name="speedometer-outline"></ion-icon>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li class="{{ Request::is('dataKandidat*') ? 'active-menu' : '' }}">
            <a href="/dataKandidat">
                <span class="icon">
                    <ion-icon name="people-outline"></ion-icon>
                </span>
                <span class="title">Data Kandidat</span>
            </a>
        </li>

        <li class="{{ Request::is('bobot*') ? 'active-menu' : '' }}">
            <a href="/bobot">
                <span class="icon">
                    <ion-icon name="scale-outline"></ion-icon>
                </span>
                <span class="title">Bobot</span>
            </a>
        </li>

         <li class="{{ Request::is('kriteria*') ? 'active-menu' : '' }}">
            <a href="/kriteria">
                <span class="icon">
                    <ion-icon name="scale-outline"></ion-icon>
                </span>
                <span class="title">Kriteria</span>
            </a>
        </li>

          <li class="{{ Request::is('sub-kriteria*') ? 'active-menu' : '' }}">
            <a href="/sub-kriteria">
                <span class="icon">
                    <ion-icon name="scale-outline"></ion-icon>
                </span>
                <span class="title">Sub Kriteria</span>
            </a>
        </li>

        <li class="{{ Request::is('penilaian*') ? 'active-menu' : '' }}">
            <a href="/penilaian">
                <span class="icon">
                    <ion-icon name="star-outline"></ion-icon>
                </span>
                <span class="title">Penilaian</span>
            </a>
        </li>

        <li class="{{ Request::is('perhitungan*') ? 'active-menu' : '' }}">
            <a href="/perhitungan">
                <span class="icon">
                    <ion-icon name="calculator-outline"></ion-icon>
                </span>
                <span class="title">Hasil Perhitungan</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span class="icon">
                    <ion-icon name="log-out-outline"></ion-icon>
                </span>
                <span class="title">Keluar</span>
            </a>
        </li>
    </ul>
</div>