# Tabibi System - Stakeholder Roles & Requirements Report

**Document Date:** May 29, 2026  
**Project:** Tabibi Healthcare Management System

---

## Executive Summary

This document defines stakeholder roles and their functional/non-functional requirements for the Tabibi healthcare system. The system supports 6 primary stakeholder types with distinct permissions, workflows, and quality attributes.

---

## 1. PATIENT

### Role Description
End-user seeking medical services, booking appointments, managing health records, and communication with healthcare providers.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F1.1 | User registration and profile creation | High |
| F1.2 | Search and filter doctors by specialty, rating, availability | High |
| F1.3 | Book, reschedule, and cancel appointments | High |
| F1.4 | View appointment history and upcoming appointments | High |
| F1.5 | Access personal medical records and lab reports | High |
| F1.6 | Receive prescriptions and view medication details | High |
| F1.7 | View radiology reports and medical imaging | High |
| F1.8 | Real-time notifications for appointment reminders | High |
| F1.9 | Chat/messaging with assigned doctor | Medium |
| F1.10 | Access AI-powered health recommendations/screening | Medium |
| F1.11 | View billing history and make payments | High |
| F1.12 | Rate and review doctors | Medium |
| F1.13 | Update personal health information (allergies, medications, conditions) | High |
| F1.14 | Export medical records (PDF format) | Medium |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF1.1 | Response Time | <2 seconds for all user interactions |
| NF1.2 | Availability | 99.5% uptime (24/7 operation) |
| NF1.3 | Data Privacy | HIPAA compliant, end-to-end encryption for sensitive data |
| NF1.4 | Data Retention | Medical records retained for 7+ years |
| NF1.5 | Security | Password hashing (bcrypt), 2FA support, SSL/TLS encryption |
| NF1.6 | Scalability | Support 100,000+ concurrent users |
| NF1.7 | Mobile Optimization | Responsive design for iOS/Android |
| NF1.8 | Accessibility | WCAG 2.1 AA compliance |
| NF1.9 | Push Notifications | FCM integration for real-time alerts |
| NF1.10 | Data Backup | Daily automated backups with 30-day retention |

---

## 2. DOCTOR (GENERAL PRACTITIONER)

### Role Description
Primary healthcare provider responsible for patient consultations, diagnosis, prescribing, and referrals.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F2.1 | View assigned patient list with key medical history | High |
| F2.2 | Schedule personal availability and manage time slots | High |
| F2.3 | Conduct appointments (with video/messaging) | High |
| F2.4 | Create and update clinical notes during consultation | High |
| F2.5 | Prescribe medications with dosage information | High |
| F2.6 | Request lab tests and radiology investigations | High |
| F2.7 | Refer patients to specialists (Lab/Radiology doctors) | High |
| F2.8 | View AI-assisted diagnosis suggestions (based on symptoms) | Medium |
| F2.9 | Access drug interaction checker | Medium |
| F2.10 | Generate prescription documents (printable/digital) | High |
| F2.11 | Communicate with lab/radiology doctors regarding tests | Medium |
| F2.12 | View test results from Lab/Radiology | High |
| F2.13 | Update patient diagnosis and treatment plans | High |
| F2.14 | Access appointment history and statistics | Medium |
| F2.15 | Manage patient follow-up appointments | Medium |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF2.1 | Response Time | <1 second for clinical data retrieval |
| NF2.2 | Availability | 99.9% during operating hours |
| NF2.3 | Data Accuracy | No data loss during operations; real-time sync |
| NF2.4 | Security | Role-based access control (RBAC), audit logs for all actions |
| NF2.5 | Compliance | HIPAA, medical records standards compliance |
| NF2.6 | Usability | Intuitive UI for clinical workflows |
| NF2.7 | Video Conferencing | <200ms latency for video consultations |
| NF2.8 | Prescription Generation | Automated PDF generation with digital signatures |
| NF2.9 | Offline Access | Limited offline access to cached patient records |
| NF2.10 | Integration | Seamless integration with Lab/Radiology systems |

---

## 3. DOCTOR (LAB SPECIALIST)

### Role Description
Laboratory specialist responsible for conducting lab tests, analyzing samples, and providing diagnostic results.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F3.1 | View pending lab test requests from General Doctors | High |
| F3.2 | Accept/reject lab test requests with notes | High |
| F3.3 | Schedule sample collection appointments | High |
| F3.4 | Record test results with reference ranges | High |
| F3.5 | Upload lab reports (PDF/images) | High |
| F3.6 | Add clinical notes and recommendations | Medium |
| F3.7 | Track sample status (received, processing, completed) | High |
| F3.8 | Notify referring doctor of completed results | High |
| F3.9 | Generate lab test batch reports | Medium |
| F3.10 | Access patient medical history for context | Medium |
| F3.11 | Manage lab equipment and calibration records | Low |
| F3.12 | View quality assurance metrics | Low |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF3.1 | Response Time | <1.5 seconds for data operations |
| NF3.2 | Data Integrity | Real-time synchronization with central system |
| NF3.3 | Accuracy | No decimal/unit conversion errors in results |
| NF3.4 | Report Generation | Automated PDF reports within 30 seconds |
| NF3.5 | Security | Encrypted storage of sensitive test data |
| NF3.6 | Traceability | Complete audit trail of test modifications |
| NF3.7 | Compliance | ISO 15189 (Laboratory standard) compliance |
| NF3.8 | Scalability | Support 1,000+ tests per day |
| NF3.9 | File Uploads | Support up to 50MB per report file |
| NF3.10 | Notifications | Real-time alerts for urgent/critical results |

---

## 4. DOCTOR (RADIOLOGY SPECIALIST)

### Role Description
Radiology specialist responsible for imaging procedures, interpreting scans, and providing radiological reports.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F4.1 | View pending radiology requests from General Doctors | High |
| F4.2 | Accept/reject radiology requests with clinical notes | High |
| F4.3 | Schedule imaging appointment slots | High |
| F4.4 | Manage imaging protocols (X-ray, MRI, CT, Ultrasound) | Medium |
| F4.5 | Upload medical images in DICOM format | High |
| F4.6 | Annotate and analyze medical images | Medium |
| F4.7 | Generate radiological reports with findings | High |
| F4.8 | Access comparison with previous imaging studies | Medium |
| F4.9 | Notify referring doctor of completed reports | High |
| F4.10 | Generate batch radiology reports | Medium |
| F4.11 | Access patient clinical history | Medium |
| F4.12 | Archive imaging data with proper retention | High |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF4.1 | Response Time | <2 seconds for image loading |
| NF4.2 | Image Quality | Lossless compression, ≥512MB per image upload |
| NF4.3 | DICOM Support | Full DICOM standard compliance |
| NF4.4 | Report Generation | Auto-generation within 1 minute |
| NF4.5 | Storage | Redundant storage with backup for all images |
| NF4.6 | Security | Encryption for medical images in transit/rest |
| NF4.7 | Performance | Handle 500+ high-resolution images daily |
| NF4.8 | Compliance | HIPAA, HL7 standards compliance |
| NF4.9 | Archive | 10+ year retention for imaging data |
| NF4.10 | Integration | PACS (Picture Archiving & Communication System) compatibility |

---

## 5. SECRETARY

### Role Description
Administrative staff managing appointments, patient registrations, and customer service workflows.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F5.1 | Register new patients in system | High |
| F5.2 | Search and retrieve patient records | High |
| F5.3 | Create/update appointment bookings | High |
| F5.4 | Send appointment reminders (SMS/Email) | High |
| F5.5 | Handle appointment cancellations and rescheduling | High |
| F5.6 | Update patient contact information | High |
| F5.7 | Manage doctor availability and schedule | High |
| F5.8 | Generate patient lists for each doctor | Medium |
| F5.9 | Process patient check-in at center | High |
| F5.10 | Handle customer inquiries and complaints | Medium |
| F5.11 | Generate appointment reports and statistics | Medium |
| F5.12 | Manage consultation fees and billing basics | Medium |
| F5.13 | Update insurance information | Medium |
| F5.14 | Send bulk notifications to patients | Medium |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF5.1 | Response Time | <1 second for patient lookup |
| NF5.2 | Availability | 99% during business hours |
| NF5.3 | Ease of Use | Minimal training required for staff |
| NF5.4 | Data Validation | Automatic field validation and error messages |
| NF5.5 | Bulk Operations | Support batch patient registration (CSV import) |
| NF5.6 | SMS Integration | Reliable SMS delivery (>95% success rate) |
| NF5.7 | Reporting | Generate reports in <5 seconds |
| NF5.8 | Security | Limited access to sensitive patient data |
| NF5.9 | Audit Trail | Track all administrative changes |
| NF5.10 | Offline Mode | Limited offline functionality for critical operations |

---

## 6. PHARMACIST

### Role Description
Pharmacy professional responsible for dispensing medications, verifying prescriptions, and patient counseling.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F6.1 | Receive and view electronic prescriptions from doctors | High |
| F6.2 | Verify prescription validity and dosage | High |
| F6.3 | Check drug interactions and contraindications | High |
| F6.4 | Manage pharmacy inventory | High |
| F6.5 | Mark prescriptions as dispensed | High |
| F6.6 | Generate pharmacy receipt/bill | High |
| F6.7 | Access patient medication history | High |
| F6.8 | Provide medication counseling notes | Medium |
| F6.9 | Handle refill requests from patients | Medium |
| F6.10 | Track medication expiry dates | High |
| F6.11 | Generate low-stock alerts | High |
| F6.12 | Manage supplier orders | Medium |
| F6.13 | Generate pharmacy sales reports | Medium |
| F6.14 | Update medicine pricing | High |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF6.1 | Response Time | <1 second for prescription lookup |
| NF6.2 | Availability | 99% during pharmacy hours |
| NF6.3 | Data Accuracy | Real-time inventory sync, no overselling |
| NF6.4 | Drug Database | Up-to-date drug formulary with interactions |
| NF6.5 | Prescription Validation | Real-time verification against prescription standards |
| NF6.6 | Audit Trail | Complete history of dispensed medications |
| NF6.7 | Security | Restricted access to medication information |
| NF6.8 | Compliance | Regulatory compliance for controlled substances |
| NF6.9 | Receipt Generation | Instant receipt printing/digital delivery |
| NF6.10 | Integration | Real-time integration with doctor prescriptions |

---

## 7. CENTER ADMIN

### Role Description
Administrative manager overseeing center operations, staff management, and performance monitoring.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F7.1 | Add/remove/manage staff (doctors, secretaries, pharmacists) | High |
| F7.2 | Assign roles and permissions to staff | High |
| F7.3 | Manage center hours of operation | High |
| F7.4 | Monitor appointment statistics and KPIs | High |
| F7.5 | Generate center performance reports | High |
| F7.6 | Manage center facilities and departments | Medium |
| F7.7 | Handle staff scheduling and shift management | High |
| F7.8 | View financial reports (revenue, expenses) | High |
| F7.9 | Manage center announcements and notices | Medium |
| F7.10 | Handle patient complaints and feedback | Medium |
| F7.11 | Approve/reject doctor timesheets | Medium |
| F7.12 | Manage integrations with payment gateways | High |
| F7.13 | Set system-wide policies and configurations | High |
| F7.14 | Monitor system health and performance | Medium |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF7.1 | Response Time | <2 seconds for report generation |
| NF7.2 | Availability | 99% during business hours |
| NF7.3 | Reporting | Generate custom reports in <30 seconds |
| NF7.4 | Data Analysis | Real-time dashboard with KPI metrics |
| NF7.5 | Security | Role-based access to financial data |
| NF7.6 | Compliance | Financial audit trails and reconciliation |
| NF7.7 | Scalability | Support multiple departments/locations |
| NF7.8 | Data Export | Support export to Excel/PDF formats |
| NF7.9 | User Management | Bulk user import/management capabilities |
| NF7.10 | Integration | Integration with accounting software |

---

## 8. SUPER ADMIN

### Role Description
System-level administrator with complete system control, user management, and platform configuration.

### Functional Requirements

| ID | Requirement | Priority |
|----|-------------|----------|
| F8.1 | Create/manage multiple centers/organizations | High |
| F8.2 | Manage all user accounts across the system | High |
| F8.3 | Assign super admin and center admin roles | High |
| F8.4 | Access system logs and audit trails | High |
| F8.5 | Monitor system performance and health | High |
| F8.6 | Manage system-wide settings and configurations | High |
| F8.7 | Handle database maintenance and backups | High |
| F8.8 | Generate system-wide reports and analytics | High |
| F8.9 | Manage API keys and integrations | High |
| F8.10 | Handle data migrations and imports | Medium |
| F8.11 | Manage system security policies | High |
| F8.12 | View all financial transactions across platform | High |
| F8.13 | Manage feature flags and A/B testing | Medium |
| F8.14 | Handle emergency shutdown/recovery | High |
| F8.15 | Manage third-party integrations | Medium |

### Non-Functional Requirements

| ID | Requirement | Specification |
|----|-------------|---------------|
| NF8.1 | Response Time | <1 second for system operations |
| NF8.2 | Availability | 99.99% uptime (4 9's) |
| NF8.3 | Security | Multi-factor authentication (MFA) required |
| NF8.4 | Audit Trail | Complete immutable audit logs of all actions |
| NF8.5 | Data Protection | Encryption for all sensitive operations |
| NF8.6 | Scalability | Support unlimited centers/organizations |
| NF8.7 | Reporting | Real-time system analytics dashboard |
| NF8.8 | Disaster Recovery | RTO <4 hours, RPO <1 hour |
| NF8.9 | Compliance | SOC 2 Type II, HIPAA, GDPR compliance |
| NF8.10 | Monitoring | 24/7 system monitoring with alerts |

---

## Cross-Cutting Non-Functional Requirements

### Security (All Roles)
- **Authentication:** Multi-factor authentication (MFA) for sensitive roles (Doctor, Pharmacist, Admin)
- **Authorization:** Fine-grained RBAC with principle of least privilege
- **Encryption:** TLS 1.3 for data in transit, AES-256 for data at rest
- **Audit Logging:** Immutable audit trails for all data modifications
- **Data Privacy:** HIPAA, GDPR, and local healthcare regulations compliance

### Performance (All Roles)
- **API Latency:** <200ms for 95% of requests
- **Database Query Time:** <500ms for complex queries
- **Page Load Time:** <2 seconds for all pages
- **Concurrent Users:** Support 100,000+ simultaneous users
- **Transaction Throughput:** 10,000+ transactions per minute

### Reliability (All Roles)
- **System Availability:** 99.9% uptime (Super Admin: 99.99%)
- **Data Backup:** Automated daily backups with geographic redundancy
- **Disaster Recovery:** RTO <2 hours, RPO <30 minutes
- **Failover:** Automatic failover for critical services
- **Monitoring:** Real-time monitoring with alerting

### Usability (All Roles)
- **UI/UX:** Intuitive, role-specific interfaces
- **Mobile Support:** Responsive design for iOS/Android/Web
- **Accessibility:** WCAG 2.1 Level AA compliance
- **Onboarding:** Role-specific training documentation
- **Localization:** Multi-language support

### Scalability (All Roles)
- **Database Scaling:** Horizontal scaling for read-heavy operations
- **API Scaling:** Microservices architecture with load balancing
- **Storage Scaling:** Cloud storage with auto-scaling capabilities
- **CDN:** Global content delivery for media and assets

### Integration (All Roles)
- **Payment Gateway:** Stripe, PayPal integration
- **SMS/Email:** Twilio, SendGrid integration
- **Video Conferencing:** Zoom, WebRTC integration
- **Push Notifications:** FCM for mobile notifications
- **EHR Systems:** HL7, FHIR standards compliance

---

## Compliance & Regulatory Requirements

| Requirement | Description |
|-------------|-------------|
| **HIPAA** | Healthcare data privacy and security standards |
| **GDPR** | General Data Protection Regulation (EU) |
| **ISO 27001** | Information security management system |
| **ISO 15189** | Laboratory standards (for Lab module) |
| **HL7/FHIR** | Healthcare data exchange standards |
| **DICOM** | Medical imaging standards (for Radiology) |
| **SOC 2 Type II** | Security and compliance certification |
| **Local Regulations** | Country-specific healthcare laws |

---

## Summary Table: Functional Requirement Count by Stakeholder

| Stakeholder | Functional Requirements | Priority High | Priority Medium | Priority Low |
|------------|----------------------|---------------|-----------------|--------------|
| Patient | 14 | 10 | 3 | 1 |
| Doctor (General) | 15 | 10 | 5 | 0 |
| Doctor (Lab) | 12 | 8 | 3 | 1 |
| Doctor (Radiology) | 12 | 8 | 3 | 1 |
| Secretary | 14 | 9 | 4 | 1 |
| Pharmacist | 14 | 10 | 3 | 1 |
| Center Admin | 14 | 7 | 6 | 1 |
| Super Admin | 15 | 10 | 4 | 1 |

---

## Non-Functional Requirements Summary

**Performance:** Sub-second response times across all critical operations  
**Security:** Multi-layer encryption, RBAC, comprehensive audit trails  
**Reliability:** 99.9% availability with disaster recovery capabilities  
**Scalability:** Support 100,000+ concurrent users with geographic distribution  
**Compliance:** HIPAA, GDPR, and healthcare standards adherence  

---

## Approval & Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Super Admin | | | |
| Center Admin | | | |
| Project Manager | | | |
| Technical Lead | | | |

---

**Document Version:** 1.0  
**Last Updated:** May 29, 2026  
**Next Review Date:** November 29, 2026
