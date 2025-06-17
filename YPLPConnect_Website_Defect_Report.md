# YPLPConnect Website Defect Report

**Project:** YPLPConnect
**Date:** 2025-06-10
**Prepared by:** GitHub Copilot

---

## 1. Summary
This document lists known and potential defects found in the YPLPConnect website and its data. Each defect is categorized by type, severity, and status. Update this document as new defects are found or resolved.

---

## 2. Defect List (Website & Application)

| ID  | Module/Area         | Description of Defect                                      | Steps to Reproduce                | Severity | Status   | Reported By | Date       |
|-----|---------------------|-----------------------------------------------------------|-----------------------------------|----------|----------|-------------|------------|
| 001 | Authentication      | Login fails with valid credentials                         | 1. Go to login page<br>2. Enter valid user<br>3. Submit | High     | Open     |           |            |
| 002 | User Registration   | No validation for duplicate email addresses                | 1. Register with existing email   | Medium   | Open     |             |            |
| 003 | Data Import         | Importing Siswa data skips some records                    | 1. Use SiswaImport<br>2. Check DB | High     | Open     |             |            |
| 004 | UI/UX               | Some pages not responsive on mobile devices                | 1. Open on mobile<br>2. Resize    | Medium   | Open     |             |            |
| 005 | Database            | Foreign key constraints missing in migrations              | Review migration files            | Medium   | Open     |             |            |
| 006 | Email Notification  | Emails not sent (Mailpit not configured in production)     | 1. Trigger email<br>2. Check inbox| Low      | Open     |             |            |
| 007 | Performance         | Dashboard loads slowly with large data sets                | 1. Login as admin<br>2. Open dashboard | Medium   | Open     |             |            |
| 008 | Security            | .env file accessible from web root (potential leak)        | 1. Access /env in browser         | Critical | Open     |             |            |
| ... | ...                 | ...                                                       | ...                               | ...      | ...      | ...         | ...        |

---

## 3. Data Defects

| ID  | Table/Model         | Description of Data Issue                                  | Example/Record                    | Severity | Status   | Reported By | Date       |
|-----|---------------------|-----------------------------------------------------------|-----------------------------------|----------|----------|-------------|------------|
| D01 | Siswa               | Missing required fields in some records                    | NISN missing in row 5             | High     | Open     |             |            |
| D02 | Guru                | Duplicate entries found                                   | Same NIP in multiple rows         | Medium   | Open     |             |            |
| D03 | Keuangan            | Negative values in payment columns                        | Payment = -1000 in record 12      | Medium   | Open     |             |            |
| ... | ...                 | ...                                                       | ...                               | ...      | ...      | ...         | ...        |

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
