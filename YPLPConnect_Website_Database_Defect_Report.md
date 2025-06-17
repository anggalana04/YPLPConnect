# YPLPConnect Website & Database Defect Report

**Project:** YPLPConnect
**Date:** 2025-06-10
**Prepared by:** GitHub Copilot

---

## 1. Summary
This document lists known and potential defects found in the YPLPConnect website, including all database tables and data. Each defect is categorized by type, severity, and status. Update this document as new defects are found or resolved.

---

## 2. Defect List (Website, Application, and Database)

| ID  | Module/Area         | Table/Model      | Description of Defect                                      | Steps to Reproduce                | Severity | Status   | Reported By | Date       |
|-----|---------------------|------------------|-----------------------------------------------------------|-----------------------------------|----------|----------|-------------|------------|
| 001 | Authentication      | users            | Login fails with valid credentials                         | 1. Go to login page<br>2. Enter valid user<br>3. Submit | High     | Open     |           |            |
| 002 | User Registration   | users            | No validation for duplicate email addresses                | 1. Register with existing email   | Medium   | Open     |             |            |
| 003 | Data Import         | siswa            | Importing Siswa data skips some records                    | 1. Use SiswaImport<br>2. Check DB | High     | Open     |             |            |
| 004 | UI/UX               | -                | Some pages not responsive on mobile devices                | 1. Open on mobile<br>2. Resize    | Medium   | Open     |             |            |
| 005 | Database            | All tables       | Foreign key constraints missing in migrations              | Review migration files            | Medium   | Open     |             |            |
| 006 | Email Notification  | users            | Emails not sent (Mailpit not configured in production)     | 1. Trigger email<br>2. Check inbox| Low      | Open     |             |            |
| 007 | Performance         | keuangan, siswa  | Dashboard loads slowly with large data sets                | 1. Login as admin<br>2. Open dashboard | Medium   | Open     |             |            |
| 008 | Security            | .env, users      | .env file accessible from web root (potential leak)        | 1. Access /env in browser         | Critical | Open     |             |            |
| 009 | Data Consistency    | guru, siswa      | Duplicate NUPTK/NISN entries possible                     | 1. Import data<br>2. Check for duplicates | High | Open |             |            |
| 010 | Data Validation     | sekolah, guru    | Email fields not validated for format                      | 1. Add invalid email              | Medium   | Open     |             |            |
| 011 | Data Integrity      | keuangan         | Negative values in jumlah_spp allowed                      | 1. Add negative payment           | Medium   | Open     |             |            |
| 012 | Data Completeness   | dokumen          | Some required fields can be null (e.g., tempat_lahir)      | 1. Add dokumen with missing data  | Low      | Open     |             |            |
| 013 | Data Import         | guru, siswa      | Import skips rows with missing required fields             | 1. Import incomplete Excel        | Medium   | Open     |             |            |
| ... | ...                 | ...              | ...                                                       | ...                               | ...      | ...      | ...         | ...        |

---

## 3. Data Defects by Table

### Table: sekolah
- Possible missing email validation
- Jenjang enum may not match all real-world cases

### Table: users
- Duplicate emails possible if validation fails
- No_hp can be null, may cause contact issues

### Table: guru
- Duplicate NUPTK/NPA possible if not enforced
- Status field may not reflect real employment status

### Table: siswa
- NISN uniqueness must be enforced
- Status field may not reflect real enrollment

### Table: dokumen
- Some fields nullable, may cause incomplete records
- File path may be missing or invalid

### Table: keuangan
- Negative or zero jumlah_spp possible
- Status may not be updated after verification

### Table: pengaduan
- Kategori enum may not cover all complaint types
- Catatan field may be empty for resolved cases

### Table: abouts
- No known defects (simple table)

---

## 4. Recommendations
- Regularly review and update this defect document.
- Assign responsible team members to each defect.
- Prioritize critical and high-severity issues.
- Retest after fixes and update status accordingly.

---

## 5. Notes
- This is a living document. Update as new defects are found or resolved.
- For detailed steps, logs, or screenshots, attach supporting files as needed.

---

*This document is auto-generated. Please review and update as needed for your project.*
