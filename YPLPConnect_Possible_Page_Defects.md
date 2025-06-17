# YPLPConnect Website Defect Report (Controller, Route, Model Analysis)

**Project:** YPLPConnect
**Date:** 2025-06-10
**Prepared by:** GitHub Copilot

---

## 1. Summary
This document lists possible and potential defects in the YPLPConnect website, based on analysis of controllers, routes, and models. Each defect is categorized by type, severity, and status. Update this document as new defects are found or resolved.

---

## 2. Defect List by Page/Feature

| ID  | Page/Feature           | Controller(s)                | Model(s)         | Description of Defect / Risk                                   | Severity | Status   |
|-----|------------------------|------------------------------|------------------|---------------------------------------------------------------|----------|----------|
| 001 | Login/Register         | Auth\*Controller             | User             | No rate limiting on login, possible brute force                | High     | Open     |
| 002 | User Management        | ManageUserController         | User, Sekolah    | No role-based access check on some actions                     | High     | Open     |
| 003 | Siswa Data             | SiswaController              | Siswa, Sekolah   | Import skips invalid rows, no feedback to user                 | Medium   | Open     |
| 004 | Guru Data              | GuruController               | Guru, Sekolah    | No validation for duplicate NUPTK/NPA                          | High     | Open     |
| 005 | Sekolah List           | SekolahController, ListSekolahController | Sekolah | Filtering/search may be case-sensitive, missing pagination     | Low      | Open     |
| 006 | Dashboard              | DashboardController          | Siswa, Guru, Keuangan, Pengaduan | Performance issues with large data sets                | Medium   | Open     |
| 007 | Keuangan               | KeuanganController           | Keuangan, Siswa, Sekolah | Negative/zero payment values possible, no strict validation | Medium   | Open     |
| 008 | Dokumen Management     | DokumenController            | Dokumen, Guru    | File upload not validated for type/size, missing error handling| High     | Open     |
| 009 | Pengaduan              | PengaduanController          | Pengaduan, User  | Kategori enum may not cover all complaint types                | Low      | Open     |
| 010 | Profile Update         | ProfileUpdateRequest         | User             | No password strength validation, email uniqueness not enforced  | Medium   | Open     |
| 011 | General                | All                         | All              | No CSRF protection on some forms, missing input sanitization   | High     | Open     |
| 012 | General                | All                         | All              | Error messages may expose sensitive info (e.g., SQL errors)    | High     | Open     |
| 013 | General                | All                         | All              | No logging for failed login or critical actions                | Medium   | Open     |
| 014 | General                | All                         | All              | No user activity/audit log                                    | Medium   | Open     |
| 015 | General                | All                         | All              | Some controllers lack try/catch for DB operations              | Medium   | Open     |
| ... | ...                    | ...                          | ...              | ...                                                           | ...      | ...      |

---

## 3. Recommendations
- Implement strict validation and error handling in all controllers.
- Add role-based access checks and CSRF protection to all forms.
- Improve user feedback for import/export operations.
- Add logging and audit trails for critical actions.
- Regularly review and update this defect document.

---

*This document is auto-generated based on code structure. Please review and update as needed for your project.*
