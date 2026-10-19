<x-layouts.app title="Edit Jadwal Periksa">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('jadwal-periksa.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Edit Jadwal Periksa</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">
            <form action="{{ route('jadwal-periksa.update', $jadwalPeriksa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-control mb-5">
                    <label for="hari" class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Hari <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <select id="hari" name="hari"
                        class="select select-bordered w-full rounded-lg @error('hari') select-error @enderror"
                        required>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <option value="{{ $hari }}" @selected(old('hari', $jadwalPeriksa->hari) === $hari)>
                            {{ $hari }}
                        </option>
                        @endforeach
                    </select>
                    @error('hari')
                    <label class="label pt-1">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <div class="form-control mb-5">
                    <label for="jam_mulai" class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Jam Mulai <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input id="jam_mulai" type="time" name="jam_mulai"
                        value="{{ old('jam_mulai', substr($jadwalPeriksa->jam_mulai, 0, 5)) }}"
                        class="input input-bordered w-full rounded-lg @error('jam_mulai') input-error @enderror"
                        required>
                    @error('jam_mulai')
                    <label class="label pt-1">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label for="jam_selesai" class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Jam Selesai <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input id="jam_selesai" type="time" name="jam_selesai"
                        value="{{ old('jam_selesai', substr($jadwalPeriksa->jam_selesai, 0, 5)) }}"
                        class="input input-bordered w-full rounded-lg @error('jam_selesai') input-error @enderror"
                        required>
                    @error('jam_selesai')
                    <label class="label pt-1">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label for="status" class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <select id="status" name="status"
                        class="select select-bordered w-full rounded-lg @error('status') select-error @enderror"
                        required>
                        <option value="Aktif" {{ old('status', $jadwalPeriksa->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $jadwalPeriksa->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                    <label class="label pt-1">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <a href="{{ route('jadwal-periksa.index') }}"
                        class="btn btn-ghost bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg px-6">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
