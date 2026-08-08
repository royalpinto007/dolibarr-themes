## What changed and why it was wrong

<!-- Describe the defect, not just the edit. "fix alignment" ages badly. -->

## Scope

- [ ] Fixed in a shared component (`core/thriveshell/`) rather than scoped to one page
- [ ] Uses design tokens — no hardcoded colour, radius, shadow or spacing
- [ ] Emits `rem`, not `em`

## Tested on

<!-- The pattern you touched almost certainly appears elsewhere. -->

- [ ] A list page
- [ ] A record card
- [ ] An edit form (two-column **and** four-column behave differently)
- [ ] A settings form
- [ ] Narrow width (mobile breakpoint is 767px)

## Interaction states

- [ ] Focus, hover
- [ ] Open select2 / date picker / account dropdown

## Contrast

- [ ] Text still meets 4.5:1 (3:1 for large), measured with alpha composited

## Screenshots

| Before | After |
|---|---|
|  |  |
