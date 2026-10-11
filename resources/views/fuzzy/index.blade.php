<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Konfigurasi Parameter Fuzzy & Aturan FAM</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola fungsi keanggotaan kurva fuzzy dan basis aturan Fuzzy Associative Memory</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Model: Kosko FAM (1992)
                </span>
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Konfigurasi Fuzzy & FAM', 'url' => null]]" />

    <!-- 1. KARTU VARIABEL INPUT & PARAMETER FUNGSI KEANGGOTAAN -->
    <div class="mb-6">
        <div class="mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Fungsi Keanggotaan 4 Kriteria Input</h3>
            <p class="text-xs text-slate-500">Parameter kurva himpunan fuzzy (Rendah, Sedang, Tinggi) untuk masing-masing kriteria</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ 
            editModalOpen: false, 
            editName: '', 
            editActionUrl: '', 
            paramA: 0, 
            paramB: 0, 
            paramC: '', 
            paramD: '',
            openEdit(name, url, a, b, c, d) {
                this.editName = name;
                this.editActionUrl = url;
                this.paramA = a;
                this.paramB = b;
                this.paramC = (c !== null && c !== undefined) ? c : '';
                this.paramD = (d !== null && d !== undefined) ? d : '';
                this.editModalOpen = true;
            }
        }">
            @foreach ($inputVariables as $var)
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                {{ $var->code }}
                            </span>
                            <h4 class="text-xs font-semibold text-slate-900">{{ $var->name }}</h4>
                        </div>
                        <span class="text-xs font-mono text-slate-500">{{ $var->min_range }} — {{ $var->max_range }}</span>
                    </div>

                    <div class="divide-y divide-slate-100 mt-2 text-xs">
                        @foreach ($var->memberships as $m)
                            <div class="py-2 flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-slate-800">{{ $m->name }}</span>
                                    <span class="text-[11px] text-slate-500 block font-mono">
                                        Kurva: {{ $m->curve_type }} &bull; [a: {{ $m->param_a }}, b: {{ $m->param_b }}{{ $m->param_c !== null ? ', c: ' . $m->param_c : '' }}{{ $m->param_d !== null ? ', d: ' . $m->param_d : '' }}]
                                    </span>
                                </div>
                                @if (auth()->user()->isAdmin())
                                    <button type="button" 
                                            @click="openEdit(@js($var->name . ' - Himpunan ' . $m->name), @js(route('fuzzy.memberships.update', $m)), {{ (float)$m->param_a }}, {{ (float)$m->param_b }}, @js($m->param_c), @js($m->param_d))"
                                            class="px-2 py-1 text-[11px] font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-md transition">
                                        Ubah
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Modal Edit Parameter Keanggotaan -->
            <div x-show="editModalOpen" 
                 class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                 style="display: none;">
                <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-200 text-left" @click.away="editModalOpen = false">
                    <h4 class="text-sm font-bold text-slate-900" x-text="'Ubah Parameter: ' + editName"></h4>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Pastikan nilai parameter batas kurva masuk akal ($a \le b \le c \le d$).</p>
                    
                    <form :action="editActionUrl" method="POST" class="space-y-3.5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Parameter a</label>
                                <input type="number" step="0.01" name="param_a" x-model="paramA" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Parameter b</label>
                                <input type="number" step="0.01" name="param_b" x-model="paramB" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Parameter c (Opsional)</label>
                                <input type="number" step="0.01" name="param_c" x-model="paramC" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Parameter d (Opsional)</label>
                                <input type="number" step="0.01" name="param_d" x-model="paramD" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                                Batal
                            </button>
                            <button type="submit" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium shadow-sm transition">
                                Simpan Parameter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. TABEL BASIS ATURAN FAM (RULE BASE) -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6" x-data="{ addRuleModal: false }">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Basis Aturan Asosiasi FAM (Rule Base)</h3>
                <p class="text-xs text-slate-500">Pemetaan premis 4 kriteria terhadap konsekuen output kelayakan penerimaan</p>
            </div>
            @if (auth()->user()->isAdmin())
                <button type="button" @click="addRuleModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Aturan FAM
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Kode Aturan</th>
                        <th class="py-3 px-4">Akademik</th>
                        <th class="py-3 px-4">Tes BTQ</th>
                        <th class="py-3 px-4">Tahfidz</th>
                        <th class="py-3 px-4">Wawancara</th>
                        <th class="py-3 px-4 text-center">Output Keputusan</th>
                        <th class="py-3 px-4 text-center">Bobot</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        @if (auth()->user()->isAdmin())
                            <th class="py-3 px-4 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($rules as $rule)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-mono font-semibold text-slate-900">{{ $rule->code }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $rule->academicMembership->name }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $rule->btqMembership->name }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $rule->tahfidzMembership->name }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $rule->interviewMembership->name }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $rule->outputMembership->name === 'DITERIMA' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                    {{ $rule->outputMembership->name }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-slate-700">{{ number_format($rule->weight, 2) }}</td>
                            <td class="py-3 px-4 text-center">
                                @if ($rule->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('fuzzy.rules.toggle', $rule) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-0.5 rounded bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-[10px] font-medium transition">
                                                {{ $rule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('fuzzy.rules.destroy', $rule) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aturan {{ $rule->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-0.5 rounded bg-white border border-rose-200 hover:bg-rose-50 text-rose-700 text-[10px] font-medium transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Aturan FAM -->
        <div x-show="addRuleModal" 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             style="display: none;">
            <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-slate-200 text-left" @click.away="addRuleModal = false">
                <h4 class="text-sm font-bold text-slate-900">Tambah Aturan Asosiasi FAM Baru</h4>
                <p class="text-xs text-slate-500 mt-1 mb-4">Tentukan kombinasi kondisi 4 kriteria dan konsekuen output kelayakan.</p>
                
                <form action="{{ route('fuzzy.rules.store') }}" method="POST" class="space-y-3.5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Aturan</label>
                        <input type="text" name="code" required placeholder="Contoh: R11" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($inputVariables as $var)
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">{{ $var->code }}</label>
                                <select name="{{ strtolower($var->code) === 'akademik' ? 'academic_membership_id' : strtolower($var->code) . '_membership_id' }}" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    @foreach ($var->memberships as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Konsekuen Output</label>
                            <select name="output_membership_id" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                @if ($outputVariable)
                                    @foreach ($outputVariable->memberships as $om)
                                        <option value="{{ $om->id }}">{{ $om->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Bobot Aturan (Weight)</label>
                            <input type="number" step="0.05" min="0.1" max="1.0" name="weight" value="1.00" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-1.5 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3.5 border-t border-slate-100">
                        <button type="button" @click="addRuleModal = false" class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                            Batal
                        </button>
                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium shadow-sm transition">
                            Simpan Aturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
