# Delnavazan Theme — Current Architecture

## Purpose
Define the Theme's current presentation responsibility and its boundary with Platform.

## Ownership boundary
Theme owns:
- templates, layout, typography and visual tokens;
- responsive/RTL presentation;
- accessible markup and interaction affordances;
- rendering of Platform-provided states/actions;
- local preview/presentation composition.

Theme does not own:
- canonical student/teacher/enrolment/Term/Lesson state;
- scheduling, attendance, payment, renewal or notification authority;
- provider credentials or external side-effect decisions;
- duplicate business records used as a second source of truth.

## Current structure
- WordPress theme templates under `theme/`.
- Shared styling through `style.css` / `theme.json`.
- Student Portal V1 scaffold is a presentation surface only and consumes Platform contracts.
- JavaScript should enhance interaction, not create hidden business authority.

## Data contract
Every displayed operational state must have a defined Platform source. If the required Platform contract does not exist yet, the Theme should show a safe placeholder/disabled state rather than inventing behaviour.

## Internationalisation / RTL
Persian-first presentation is supported with RTL semantics. Latin identifiers, times, codes and mixed-language content must remain readable and directionally stable.

## Accessibility
Semantic headings, labels, keyboard access, focus visibility, sufficient contrast and responsive layouts are baseline requirements.

## Staleness trigger
Update after accepted template/component architecture changes, new portal surfaces, changed Platform/Theme ownership, or new data/action contracts. Maximum review interval: 45 days while Theme development is active.
