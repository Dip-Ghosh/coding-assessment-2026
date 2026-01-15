# Backend PHP Coding Assessment - "Monday Morning Takeover"

A realistic 2-3 hour take-home assessment simulating code inheritance from a previous developer.

## Overview

**Scenario:** Candidate inherits incomplete code with bugs. Client demo in 2 days.
**Time:** 2-3 hours for candidates | 30-45 minutes to review
**Requirements:** PHP 7.4+ (Composer packages allowed for PDF generation)

## Quick Start

### Send to Candidate

```bash
cd invoice-system
zip -r candidate-assessment.zip . \
  -x "*.git*" \
  -x "*docs/SCORING_RUBRIC.md" \
  -x "*docs/ASSESSMENT_VARIANTS.md" \
  -x "*HIRING_TEAM_GUIDE.md"
```

Email the zip with instructions to read `docs/CANDIDATE_INSTRUCTIONS.md`

### Review Submission

1. Read their `HANDOVER.md` (10 min)
2. Check Git history (5 min)
3. Review code changes (15 min)
4. Score using rubric (10 min)

**See `HIRING_TEAM_GUIDE.md` for detailed instructions.**

## File Structure

```
invoice-system/
├── src/                   - PHP code with 5 intentional bugs
├── data/                  - JSON files (one corrupted)
├── tests/                 - Test suite (2 pass, 3 fail initially)
├── docs/
│   ├── CANDIDATE_INSTRUCTIONS.md  ✓ Send to candidates
│   ├── SCORING_RUBRIC.md          ✗ Internal only
│   └── ASSESSMENT_VARIANTS.md     ✗ Internal only
├── README.md              - "Previous dev" notes (part of scenario)
└── run_tests.php          - Test runner

HIRING_TEAM_GUIDE.md       ← Read this first
README.md                  ← This file
```

## What It Tests

| Area | Weight | What |
|------|--------|------|
| Technical Skills | 40% | Debugging, code comprehension, clean fixes |
| Judgment & Process | 30% | Prioritization, realism, pragmatic trade-offs |
| Communication | 30% | Documentation, client emails, honesty |

## Intentional Bugs

1. **qty/quantity mismatch** - Array key inconsistency → totals return $0
2. **File overwrite** - saveToFile() replaces instead of appending
3. **JSON corruption** - Missing brace in invoices.json (line ~30)
4. **No validation** - Negative prices/quantities not checked
5. **Poor ID generation** - Timestamp-based (collision risk)

## Scoring Bands

- **90-100:** Exceptional - Strong hire
- **75-89:** Strong - Proceed to interview
- **60-74:** Adequate - Interview with reservations
- **<60:** Likely reject

## Assessment Variants

Three variants available (same difficulty, different focus):

- **Base:** Balanced - PDF blocker, general backend (default)
- **Variant A:** PDF is critical - problem-solving focus
- **Variant B:** Discount logic critical - business logic focus
- **Variant C:** Performance issue - optimization focus

See `docs/ASSESSMENT_VARIANTS.md` for implementation details.

## Verify It Works

```bash
cd invoice-system
php -l src/*.php tests/*.php    # Check syntax
php run_tests.php               # Should show 2 pass, 3 fail
```

**Expected:** Tests fail initially (bugs are intentional).

## Key Features

- ✅ Realistic scenario (inheriting messy code)
- ✅ Logic bugs (not syntax errors)
- ✅ Tests judgment + technical + communication
- ✅ Git history reveals actual process
- ✅ Clear scoring rubric
- ✅ Time-boxed (prevents over-engineering)
- ✅ No external dependencies

## Documentation

| File | Audience | Purpose |
|------|----------|---------|
| `HIRING_TEAM_GUIDE.md` | Hiring team | Complete usage guide |
| `invoice-system/docs/CANDIDATE_INSTRUCTIONS.md` | Candidates | Full instructions |
| `invoice-system/docs/SCORING_RUBRIC.md` | Reviewers | 100-point scoring system |
| `invoice-system/docs/ASSESSMENT_VARIANTS.md` | Hiring team | Variant instructions |

## Requirements

**Candidate:** PHP 7.4+, text editor, Git
**Reviewer:** PHP 7.4+, 30-45 minutes

## Red Flags

- Complete rewrite from scratch
- Claims everything is perfect
- One commit at the end
- Blames previous developer
- Spent 6+ hours

## Green Flags

- Fixed critical bugs first
- Honest about limitations
- Professional client communication
- Good Git history (10+ commits)
- Pragmatic solutions

---

**Start here:** Read `HIRING_TEAM_GUIDE.md` for complete instructions.
