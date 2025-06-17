# YPLPConnect Database & Data Documentation

## 1. Table: sekolah
- **npsn** (char[8], PK)
- **nama** (string[100])
- **jenjang** (enum: SD, SMP, SMA, SMK)
- **alamat** (string[255])
- **email** (string, nullable)
- **timestamps**

## 2. Table: users
- **id** (bigint, PK)
- **npsn** (char[8], FK to sekolah, nullable)
- **name** (string)
- **email** (string, unique)
- **no_hp** (string, nullable)
- **email_verified_at** (timestamp, nullable)
- **password** (string)
- **role** (enum: operator_sekolah, operator_yayasan)
- **remember_token** (string, nullable)
- **timestamps**

## 3. Table: guru
- **nuptk** (char[16], PK)
- **npsn** (char[8], FK to sekolah)
- **npa** (char[16], unique, nullable)
- **nama** (string[100])
- **jenis_kelamin** (enum: L, P)
- **tempat_lahir** (string[100])
- **tanggal_lahir** (date)
- **alamat** (string[255])
- **no_hp** (string[15], nullable)
- **status** (enum: Aktif, Nonaktif)
- **timestamps**

## 4. Table: siswa
- **nisn** (char[10], PK)
- **npsn** (char[8], FK to sekolah)
- **nama** (string[100])
- **kelas** (string[15])
- **jenis_kelamin** (enum: L, P)
- **tempat_lahir** (string[100])
- **tanggal_lahir** (date)
- **alamat** (string[255])
- **status** (enum: Aktif, Nonaktif)
- **timestamps**

## 5. Table: dokumen
- **id** (string[14], PK)
- **nuptk** (char[16], FK to guru)
- **nama** (string[100])
- **tempat_lahir** (string[100], nullable)
- **tanggal_lahir** (date, nullable)
- **alamat_unit_kerja** (string[255], nullable)
- **jenis_sk** (enum: SK Pengangkatan, SK Pensiun, SK Mutasi, SK Kenaikan Pangkat, SK Kepala Sekolah, SK Guru)
- **status** (enum: Menunggu, Diterima, Diproses, Selesai, Ditolak)
- **file_path** (string[255], nullable)
- **submitted_by** (bigint, FK to users, nullable)
- **verified_by** (bigint, FK to users, nullable)
- **verified_at** (timestamp, nullable)
- **catatan** (text, nullable)
- **timestamps**

## 6. Table: keuangan
- **id** (string, PK)
- **npsn** (char[8], FK to sekolah)
- **tahun** (year)
- **bulan** (enum: Januari-Desember)
- **jumlah_spp** (decimal[15,2], default 0)
- **status** (enum: Menunggu, Disetujui, Ditolak)
- **bukti_path** (string[255], nullable)
- **verified_by** (bigint, FK to users, nullable)
- **verified_at** (timestamp, nullable)
- **catatan** (text, nullable)
- **timestamps**

## 7. Table: pengaduan
- **id** (string[12], PK)
- **npsn** (char[8], FK to sekolah)
- **judul** (string[100])
- **deskripsi** (text)
- **kategori** (enum: Kendala Teknis, Pelayanan, Lainnya)
- **bukti_path** (string[255], nullable)
- **status** (enum: Menunggu, Diproses, Selesai)
- **submitted_by** (bigint, FK to users, nullable)
- **verified_by** (bigint, FK to users, nullable)
- **verified_at** (timestamp, nullable)
- **catatan** (text, nullable)
- **timestamps**

## 8. Table: abouts
- **id** (bigint, PK)
- **timestamps**

---

# Data Notes
- All foreign keys are enforced for data integrity.
- Enum fields restrict values to valid options only.
- Timestamps are used for created/updated tracking.

# Data Defect Documentation
- Please refer to the defect report template for tracking issues in each table and data field.

---

*This document is auto-generated. Please review and update as needed for your project.*
