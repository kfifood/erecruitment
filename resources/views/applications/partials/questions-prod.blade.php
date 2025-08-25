<!-- Step 5: Pertanyaan Produksi -->
<div class="step" id="step5">
    <h4 class="step-header">5. Daftar Pertanyaan (jawablah dengan Ya / Tidak dan Berikan Penjelasan)</h4>

    <!-- Kelompok Pertanyaan Kesiapan Kerja -->
    <div class="question-group">
        <h5 class="section-title">Kesiapan Kerja</h5>

        <!-- Pertanyaan 1 -->
        <div class="mb-3">
            <label class="form-label required-field">1. Apakah Anda Bersedia Untuk Kerja Shift?</label>
            <div class="d-flex align-items-center gap-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="shift_work" id="shift_work_yes" value="Ya"
                        required>
                    <label class="form-check-label" for="shift_work_yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="shift_work" id="shift_work_no" value="Tidak">
                    <label class="form-check-label" for="shift_work_no">Tidak</label>
                </div>
            </div>
        </div>

        <!-- Pertanyaan 2 -->
        <div class="mb-3">
            <label class="form-label required-field">2. Apakah Anda Bersedia Kerja Dengan Sistem Borongan?</label>
            <div class="d-flex align-items-center gap-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="piecework_system" id="piecework_system_yes"
                        value="Ya" required>
                    <label class="form-check-label" for="piecework_system_yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="piecework_system" id="piecework_system_no"
                        value="Tidak">
                    <label class="form-check-label" for="piecework_system_no">Tidak</label>
                </div>
            </div>
        </div>
        <!-- Pertanyaan 3 -->
        <div class="mb-3">
            <label class="form-label required-field">3. Apakah Anda bersedia jika dipindah posisikan di PT Kirana Food
                International?</label>
            <div class="d-flex align-items-center gap-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="position_transfer" id="position_transfer_yes"
                        value="Ya" required>
                    <label class="form-check-label" for="position_transfer_yes">Ya</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="position_transfer" id="position_transfer_no"
                        value="Tidak">
                    <label class="form-check-label" for="position_transfer_no">Tidak</label>
                </div>
            </div>
        </div>

        <!-- Kelompok Pertanyaan Pengalaman -->
        <div class="question-group">
            <h5 class="section-title">Pengalaman</h5>

            <!-- Pertanyaan 4 -->
            <div class="mb-3">
                <label class="form-label required-field">4. Apakah memiliki pengalaman Organisasi? Jika ya, Jelaskan
                    posisi dan tugas anda</label>
                <textarea class="form-control" name="organization_experience" rows="3" required></textarea>
            </div>
        </div>

        <!-- Kelompok Pertanyaan Kesehatan -->
        <div class="question-group">
            <h5 class="section-title">Kesehatan</h5>

            <!-- Pertanyaan 5 -->
            <div class="mb-3">
                <label class="form-label required-field">5. Apakah saat ini anda sedang sakit?</label>
                <div class="d-flex align-items-center gap-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="current_sickness" id="current_sickness_yes"
                            value="Ya" required>
                        <label class="form-check-label" for="current_sickness_yes">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="current_sickness" id="current_sickness_no"
                            value="Tidak">
                        <label class="form-check-label" for="current_sickness_no">Tidak</label>
                    </div>
                </div>
            </div>
            <!-- Pertanyaan 6 -->
            <div class="mb-3">
                <label class="form-label required-field">6. Apakah dalam 6 bulan terakhir anda pernah sakit?</label>
                <div class="d-flex align-items-center gap-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="recent_sickness" id="recent_sickness_yes"
                            value="Ya" required>
                        <label class="form-check-label" for="recent_sickness_yes">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="recent_sickness" id="recent_sickness_no"
                            value="Tidak">
                        <label class="form-check-label" for="recent_sickness_no">Tidak</label>
                    </div>
                </div>
                <div class="mt-3 ps-4">
                    <small class="text-muted">Jika ya, sebutkan jenis penyakit:</small>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="typhoid" id="typhoid" value="Ya">
                        <label class="form-check-label" for="typhoid">Typus</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hepatitis" id="hepatitis" value="Ya">
                        <label class="form-check-label" for="hepatitis">Hepatitis</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tuberculosis" id="tuberculosis"
                            value="Ya">
                        <label class="form-check-label" for="tuberculosis">TBC</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cyst" id="cyst" value="Ya">
                        <label class="form-check-label" for="cyst">Kista</label>
                    </div>
                </div>
            </div>

            <!-- Pertanyaan 7 -->
            <div class="mb-3">
                <label class="form-label required-field">7. Apakah anda buta warna atau mempunyai penyakit
                    menular?</label>

                <div class="row">
                    <!-- Kolom Buta Warna -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="color_blind" id="color_blind_yes"
                                    value="Ya" required>
                                <label class="form-check-label" for="color_blind_yes">Ya (Buta Warna)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="color_blind" id="color_blind_no"
                                    value="Tidak">
                                <label class="form-check-label" for="color_blind_no">Tidak</label>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Penyakit Menular -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contagious_disease"
                                    id="contagious_disease_yes" value="Ya" required>
                                <label class="form-check-label" for="contagious_disease_yes">Ya (Penyakit
                                    Menular)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contagious_disease"
                                    id="contagious_disease_no" value="Tidak">
                                <label class="form-check-label" for="contagious_disease_no">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kelompok Pertanyaan Lainnya -->
            <div class="question-group">
                <h5 class="section-title">Lainnya</h5>

                <!-- Pertanyaan 8 -->
                <div class="mb-3">
                    <label class="form-label required-field">8. Apakah anda pernah berurusan dengan Polisi karena tindak
                        kejahatan / Narkoba?</label>
                    <div class="d-flex align-items-center gap-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="police_record" id="police_record_yes"
                                value="Ya" required>
                            <label class="form-check-label" for="police_record_yes">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="police_record" id="police_record_no"
                                value="Tidak">
                            <label class="form-check-label" for="police_record_no">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Pertanyaan 9 -->
                <div class="mb-3">
                    <label class="form-label required-field">9. Apakah anda terikat kontrak kerja dengan perusahaan
                        ditempat kerja sekarang?</label>
                    <div class="d-flex align-items-center gap-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="current_contract"
                                id="current_contract_yes" value="Ya" required>
                            <label class="form-check-label" for="current_contract_yes">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="current_contract"
                                id="current_contract_no" value="Tidak">
                            <label class="form-check-label" for="current_contract_no">Tidak</label>
                        </div>
                    </div>
                </div>

                <!-- Pertanyaan 10 -->
                <div class="mb-3">
                    <label class="form-label required-field">10. Macam pekerjaan yang bagaimana anda tidak
                        sukai?</label>
                    <textarea class="form-control" name="disliked_job_types" rows="2" required></textarea>
                </div>

                <!-- Pertanyaan 11 -->
                <div class="mb-3">
                    <label class="form-label required-field">11. Apakah anda bisa mengaplikasikan komputer / mesin
                        produksi?</label>
                    <textarea class="form-control" name="computer_machine_skills" rows="2" required></textarea>
                    <small class="text-muted">Sebutkan kemampuan spesifik yang dimiliki</small>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="declaration2" required>
                <label class="form-check-label" for="declaration2">
                    Dengan ini saya menyatakan bahwa keterangan yang saya berikan di atas adalah benar. Bilamana
                    ternyata terdapat ketidak-benaran, saya sanggup menerima sanksi yang berlaku.
                </label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-nav" onclick="prevStep(5, 4)">
                    <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Lamaran
                </button>
            </div>
        </div>