# DOKUMENTASI FORMULASI MATEMATIKA METODE FUZZY ASSOCIATIVE MEMORY (FAM)
## SPK Penerimaan Santri Baru MA Pesantren Ell Firdaus Labusel

---

### 1. Pengantar Metode FAM
**Fuzzy Associative Memory (FAM)** diperkenalkan oleh Bart Kosko (1992) sebagai bentuk jaringan saraf tiruan fuzzy yang memetakan pasangan himpunan fuzzy input ke himpunan fuzzy output. Dalam Sistem Pendukung Keputusan Penerimaan Santri Baru MA Pesantren Ell Firdaus, FAM menghubungkan 4 kriteria penilaian:
1. **$X_1$ = Nilai Akademik / Rapor**
2. **$X_2$ = Tes Baca Tulis Al-Qur'an (BTQ)**
3. **$X_3$ = Tes Hafalan Al-Qur'an (Tahfidz)**
4. **$X_4$ = Tes Wawancara**

Menuju variabel output:
- **$Y$ = Kelayakan Penerimaan Santri** (`TIDAK DITERIMA` / `DITERIMA`)

---

### 2. Tahap 1: Fuzzifikasi (Fuzzification)
Fuzzifikasi mengubah nilai tegas (crisp value) $x \in [0, 100]$ menjadi derajat keanggotaan $\mu(x) \in [0, 1]$.

Bentuk kurva yang didukung oleh sistem:

#### A. Kurva Trapesium Kiri (Shoulder Left - Himpunan Rendah)
$$\mu_{\text{Rendah}}(x) = \begin{cases} 
1, & x \le a \\ 
\frac{b - x}{b - a}, & a < x < b \\ 
0, & x \ge b 
\end{cases}$$

#### B. Kurva Segitiga (Triangular - Himpunan Sedang)
$$\mu_{\text{Sedang}}(x) = \begin{cases} 
0, & x \le a \text{ atau } x \ge c \\ 
\frac{x - a}{b - a}, & a < x \le b \\ 
\frac{c - x}{c - b}, & b < x < c 
\end{cases}$$

#### C. Kurva Trapesium Kanan (Shoulder Right - Himpunan Tinggi)
$$\mu_{\text{Tinggi}}(x) = \begin{cases} 
0, & x \le a \\ 
\frac{x - a}{b - a}, & a < x < b \\ 
1, & x \ge b 
\end{cases}$$

---

### 3. Tahap 2: Pembentukan Matriks FAM & Encoding
Aturan asosiasi menghubungkan premis input $A_k = (A_{1k}, A_{2k}, A_{3k}, A_{4k})$ ke konsekuen output $B_k$.

Firing strength ($\alpha_k$) dari aturan ke-$k$ dihitung dengan t-norm minimum:
$$\alpha_k = \min(\mu_{X_1, k}(x_1), \mu_{X_2, k}(x_2), \mu_{X_3, k}(x_3), \mu_{X_4, k}(x_4))$$

Sistem mendukung 2 metode encoding Kosko:
1. **Correlation-Minimum Encoding**:
   $$M_k = A_k \circ B_k \implies \mu_{B'_k}(y) = \min(\alpha_k, \mu_{B_k}(y))$$
2. **Correlation-Product Encoding**:
   $$M_k = A_k \cdot B_k \implies \mu_{B'_k}(y) = \alpha_k \cdot \mu_{B_k}(y)$$

---

### 4. Tahap 3: Komposisi Fuzzy (Inference Composition)
Menggabungkan luaran dari seluruh aturan aktif ($k = 1, 2, \dots, R$):

1. **Max-Min Composition**:
   $$\mu_{\text{Agg}}(y) = \max_{k} \left( \min(\alpha_k, \mu_{B_k}(y)) \right)$$
2. **Max-Product Composition**:
   $$\mu_{\text{Agg}}(y) = \max_{k} \left( \alpha_k \cdot \mu_{B_k}(y) \right)$$

---

### 5. Tahap 4: Defuzzifikasi (Defuzzification)
Mengubah himpunan fuzzy agregat menjadi nilai tegas tunggal (skor akhir FAM $z^* \in [0, 100]$):

1. **Weighted Average (Rata-rata Terbobot)**:
   $$z^* = \frac{\sum_{k=1}^R \alpha_k \cdot w_k \cdot \bar{y}_k}{\sum_{k=1}^R \alpha_k \cdot w_k}$$
   di mana $\bar{y}_k$ adalah nilai representatif (center/centroid) himpunan output aturan ke-$k$, dan $w_k$ adalah bobot aturan.

2. **Winner-Take-All (WTA)**:
   Memilih aturan dengan $\alpha_k$ maksimum:
   $$k^* = \arg\max_k (\alpha_k)$$
   $$z^* = \bar{y}_{k^*}$$

3. **Centroid (Center of Gravity - Discretized)**:
   $$z^* = \frac{\sum_{j=1}^m y_j \cdot \mu_{\text{Agg}}(y_j)}{\sum_{j=1}^m \mu_{\text{Agg}}(y_j)}$$

---

### 6. Tahap 5: Keputusan Seleksi & Pemeringkatan
- **Keputusan**:
  $$\text{Keputusan} = \begin{cases} 
  \text{DITERIMA}, & z^* \ge \text{Passing Grade} \\ 
  \text{TIDAK DITERIMA}, & z^* < \text{Passing Grade} 
  \end{cases}$$
- **Pemeringkatan (Ranking)**:
  Diurutkan berdasarkan $z^*$ dari nilai tertinggi ke terendah. Jika terdapat nilai identik (*tie*), peringkat ditentukan berurutan berdasarkan kriteria prioritas (Tahfidz $\to$ BTQ $\to$ Akademik $\to$ Wawancara).
