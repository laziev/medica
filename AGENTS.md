# Project: Aesthetic Medicine CRM / Medical Information System

## Purpose
This is an internal system for an aesthetic medicine clinic.

It combines:
- patient records
- scheduling (calendar)
- medical questionnaires
- procedures and corrections
- consent documents and signatures
- photos and files
- follow-up reminders

This is NOT a public SaaS.
This is a privacy-sensitive internal system.

---

## Tech Stack (STRICT)
- Laravel 12
- FilamentPHP 5
- PHP 8.3+
- MySQL or MariaDB
- Ubuntu server (aaPanel)
- HTTPS only

DO NOT upgrade framework versions unless explicitly requested.

---

## Core Principles

1. Plan first, code second.
2. Do not generate large amounts of code without request.
3. Prefer Laravel + Filament native solutions.
4. Avoid unnecessary packages.
5. Keep the system simple, maintainable, and extensible.
6. Do not overengineer.
7. Prefer boring, predictable architecture over clever solutions.
8. Always consider future scalability (multi-doctor, multi-location).

---

## Domain Rules (CRITICAL)

### Separation of concerns

DO NOT merge these concepts:
- Appointment (calendar booking)
- Visit (actual clinic encounter)
- Treatment course (procedure + corrections)
- Treatment session (individual procedure or correction)

These must be separate domain concepts.

---

### Patients

Patients are not just contacts.
They include:
- personal data
- medical data
- documents
- questionnaire history
- procedures
- photos

---

### Leads

Appointments may exist WITHOUT a full patient profile.
Support “lead” or partial contact data.

---

### Medical Data

Medical information is sensitive.

- Must be separated from general patient data.
- Must support restricted access (doctor vs manager).
- Must be versioned over time.

---

### Medical Questionnaires

- Must be template-driven
- Must support versioning
- Must preserve historical answers
- Must NOT overwrite previous data

---

### Consent Documents

- Must be template-based
- Must support versions
- Must store:
  - template ID
  - version
  - locale
  - timestamp
- Must support digital signatures

---

### Procedures and Corrections

- A treatment course = main procedure + corrections
- A treatment session = single execution (procedure or correction)

Each session must store:
- date
- product used
- amount used
- zones
- comments
- photos

DO NOT add redundant fields like “what was corrected” if data already describes it.

---

### Photos

- Photos belong to treatment sessions
- NOT directly to patients
- Must support multiple photos per session
- Must support private storage

---

### Pricing

- Price is NOT fixed per procedure
- Each appointment/session may have its own negotiated price

---

### Statuses

- Must be configurable (database-based)
- Must support color
- DO NOT hardcode as enums unless necessary

---

### Follow-ups

- Managers can create reminders
- Must support:
  - date
  - relation to patient or lead
  - status

---

### Locations and Rooms

- System must support:
  - multiple locations
  - multiple rooms
  - future multiple doctors

---

### Calendar

- Must support:
  - appointments
  - durations
  - flexible schedule
  - shifts
  - blocked time (lunch, breaks)
- Must be mobile-friendly

---

## Multilingual (FUTURE-READY)

The system must support future multilingual use:
- Russian
- Hebrew
- English

Rules:

- Do NOT hardcode questionnaire or document text.
- Use template-driven structures.
- Store locale for:
  - questionnaires
  - signed documents
- Answers must be language-independent.
- PDF exports must reflect original language.
- Adding new languages must NOT require DB redesign.

---

## File Storage & Security

- Files must be private by default.
- No public direct access.
- Must support future S3-compatible storage.
- Medical data must be treated as sensitive.

---

## Permissions

Roles:
- Doctor
- Manager

Rules:
- Doctor has full access
- Manager has restricted access to medical data
- Permissions must be flexible and configurable
- DO NOT hardcode role logic in multiple places

---

## Filament Guidelines

- Use Filament Resources for main entities
- Use Relation Managers for nested data
- Use Actions for inline workflows
- Avoid custom JS unless necessary
- Optimize for mobile usage
- Keep forms compact and practical

---

## Database Guidelines

- Use foreign keys
- Use indexes where appropriate
- Use soft deletes selectively
- Use audit fields (created_at, updated_at)
- Avoid premature JSON unless justified
- Prefer explicit relational structure

---

## What NOT to do

- Do NOT merge appointment, visit, and procedure into one table
- Do NOT store all photos on patient level
- Do NOT overwrite medical questionnaire history
- Do NOT hardcode statuses
- Do NOT assume single doctor system
- Do NOT assume single language system
- Do NOT start with complex features (PDF, signatures, messaging)

---

## Implementation Order (HIGH LEVEL)

1. Users / roles / permissions
2. Patients / leads
3. Locations / rooms
4. Appointment statuses
5. Appointments / calendar
6. Visits / treatment courses / sessions
7. File handling (photos)
8. Medical questionnaires (versioning)
9. Consent documents and signatures
10. Follow-ups
11. Future features (notifications, batch tracking, multilingual UI)

---

## Code Generation Rules

When generating code:

- Only generate what is explicitly requested
- Do not modify architecture silently
- Follow approved schema
- Keep code clean and minimal
- Do not introduce new patterns without justification

---

## Change Requests

When asked to change something:

1. Analyze impact first
2. Identify affected parts (DB, models, UI, permissions)
3. Propose minimal safe change
4. Only then generate code

---

## Tone

- Be direct
- Be practical
- Avoid unnecessary explanations
- Prefer clear decisions over multiple vague options
