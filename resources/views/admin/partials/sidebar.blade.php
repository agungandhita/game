<div>
    <div class="flex items-start">
        <nav id="sidebar" class="lg:min-w-[250px] w-max max-lg:min-w-8">
            <div id="sidebar-collapse-menu" style="height: calc(100vh - 72px)"
                class="bg-white shadow-xl h-screen fixed py-8 px-6 top-[72px] left-0 overflow-auto z-[99] lg:min-w-[250px] lg:w-max max-lg:w-0 max-lg:invisible transition-all duration-500 border-r border-gray-100">
                <div class="flex flex-col h-full justify-between">
                    <div>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-gray-600 text-sm flex items-center hover:bg-blue-50 hover:text-blue-600 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : '' }}">
                                    <i class="fas fa-home w-[20px] h-[20px] mr-3 text-center"></i>
                                    <span>Beranda Admin</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.grades.index') }}"
                                    class="text-gray-600 text-sm flex items-center hover:bg-teal-50 hover:text-teal-600 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->routeIs('admin.grades.*') ? 'bg-teal-50 text-teal-600 font-bold' : '' }}">
                                    <i class="fas fa-graduation-cap w-[20px] h-[20px] mr-3 text-center"></i>
                                    <span>Nilai Siswa</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.questions.index') }}"
                                    class="text-gray-600 text-sm flex items-center hover:bg-indigo-50 hover:text-indigo-600 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->routeIs('admin.questions.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : '' }}">
                                    <i class="fas fa-book-open w-[20px] h-[20px] mr-3 text-center"></i>
                                    <span>Bank Soal</span>
                                </a>
                            </li>
                        </ul>

                        <div class="mt-10">
                            <h6 class="text-gray-400 text-[11px] uppercase tracking-widest font-bold px-4 mb-4">Pengaturan Sistem</h6>
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="text-gray-600 text-sm flex items-center hover:bg-amber-50 hover:text-amber-600 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-amber-50 text-amber-600 font-bold' : '' }}">
                                        <i class="fas fa-user-friends w-[20px] h-[20px] mr-3 text-center"></i>
                                        <span>Kelola Guru/Siswa</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings.index') }}"
                                        class="text-gray-600 text-sm flex items-center hover:bg-gray-100 hover:text-gray-900 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 text-gray-900 font-bold' : '' }}">
                                        <i class="fas fa-sliders-h w-[20px] h-[20px] mr-3 text-center"></i>
                                        <span>Konfigurasi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-auto pt-10 pb-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-red-500 text-sm flex items-center hover:bg-red-50 rounded-xl px-4 py-3 transition-all duration-300 group">
                                <i class="fas fa-sign-out-alt w-[20px] h-[20px] mr-3 text-center group-hover:transform group-hover:translate-x-1 transition-transform"></i>
                                <span class="font-semibold">Keluar Sistem</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
