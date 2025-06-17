# YPLPConnect Website Defect Report (CRUD & Common Errors)

**Project:** YPLPConnect
**Date:** 2025-06-10
**Prepared by:** GitHub Copilot

---

## 1. Summary
This document lists possible and made-up defects related to basic CRUD operations and other common issues that could occur in the YPLPConnect website. These are hypothetical but realistic errors for documentation and review purposes.

---

## 2. CRUD & Common Error Defect List

| ID  | Entity/Page      | Operation | Description of Defect / Risk                                   | Severity | Status   |
|-----|------------------|-----------|---------------------------------------------------------------|----------|----------|
| 001 | Siswa            | Create    | Form allows submission with missing required fields            | High     | Open     |
| 002 | Siswa            | Read      | Pagination not implemented, slow with large data               | Medium   | Open     |
| 003 | Siswa            | Update    | No validation on NISN uniqueness during update                 | High     | Open     |
| 004 | Siswa            | Delete    | No confirmation dialog, accidental deletion possible           | High     | Open     |
| 005 | Guru             | Create    | Accepts invalid phone number formats                           | Medium   | Open     |
| 006 | Guru             | Update    | Update form does not check for duplicate NUPTK                 | High     | Open     |
| 007 | Guru             | Delete    | Deleting guru does not check for related dokumen/keuangan      | Medium   | Open     |
| 008 | Sekolah          | Create    | Email field not validated, accepts invalid emails              | Medium   | Open     |
| 009 | Sekolah          | Update    | Jenjang enum not enforced, accepts invalid values              | Medium   | Open     |
| 010 | Sekolah          | Delete    | Deleting sekolah does not cascade to related users/guru/siswa  | High     | Open     |
| 011 | Dokumen          | Create    | File upload accepts non-PDF files                              | Medium   | Open     |
| 012 | Dokumen          | Update    | No check for file size/type on update                          | Medium   | Open     |
| 013 | Dokumen          | Delete    | Deleting dokumen does not remove file from storage             | Low      | Open     |
| 014 | Keuangan         | Create    | Allows negative or zero payment values                         | Medium   | Open     |
| 015 | Keuangan         | Update    | No audit log for changes to payment status                     | Medium   | Open     |
| 016 | Keuangan         | Delete    | No check for related reports before deletion                   | Low      | Open     |
| 017 | Pengaduan        | Create    | Kategori field accepts invalid values                          | Low      | Open     |
| 018 | Pengaduan        | Update    | No notification sent to user on status change                  | Low      | Open     |
| 019 | Pengaduan        | Delete    | No soft delete, permanent loss of complaint data               | Medium   | Open     |
| 020 | User             | Create    | Password strength not enforced                                 | High     | Open     |
| 021 | User             | Update    | Email uniqueness not checked on update                         | High     | Open     |
| 022 | User             | Delete    | Deleting user does not reassign or handle related data         | Medium   | Open     |
| 023 | General          | All       | No rollback on failed DB transaction, partial data saved       | High     | Open     |
| 024 | General          | All       | No error message shown on failed operation                     | Medium   | Open     |
| 025 | General          | All       | No input sanitization, possible XSS/SQL injection              | High     | Open     |
| ... | ...              | ...       | ...                                                           | ...      | ...      |

---

## 3. Recommendations
- Add validation and confirmation dialogs to all CRUD forms.
- Enforce unique and required constraints at both DB and application level.
- Implement pagination and search for all list pages.
- Add audit logs and notifications for critical changes.
- Sanitize all user input and handle errors gracefully.

---

*This document is for demonstration purposes. Please review and update as needed for your project.*
