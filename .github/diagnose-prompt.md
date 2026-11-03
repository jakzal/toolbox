# Build failure diagnosis

You are diagnosing a failed build of jakzal/toolbox — a PHAR bundling PHP
quality-assurance tools. Each tool declares its installation in a
`resources/*.json` file, using one of several installation methods
documented in CONTRIBUTING.md ("Adding a new tool → Commands").

`failed-debug.log` (attached) contains the logs of every failed step of one
GitHub Actions run. Jobs come from a matrix of recent PHP versions and
dependency sets; job names look like "Build and test (php: <version>, deps:
<high|low>)" and "Run integration tests (php: <version>)". The actual
supported PHP versions can be seen in .github/workflows/build.yml.

The repository — including the `Makefile` with the exact build steps — is
checked out in the current directory; read it to trace a failing step to the
Makefile target or the tool definition involved. Note that `make update` runs
`composer update`, so unattended builds also surface dependency drift and
upstream tool changes.

Your task:

1. If the log is empty or only says "No logs available", state that the failure
   was likely runner-level and needs no analysis.
2. For EACH failed matrix job in the log, classify the root cause as exactly one
   of:
   - `broken-install`   — a tool download/install broke (404, renamed release,
                          moved binary, missing dependency, upstream changed)
   - `signature-change` — a checksum, GPG/PHAR signature, or key of a downloaded
                          artifact failed verification or was updated upstream
   - `transient`        — network hiccup, rate limit, DNS, timeout, runner
                          error; restarting the failed job may simply fix it
   - `other`            — anything else; describe it briefly
3. For each failure cite the decider evidence: quote the exact log lines (and
   for `signature-change`, the expected vs. received values). Name the affected
   tool and the involved Makefile target or workflow step.
4. Suggest a concrete, minimal resolution per failure. For `transient`, say
   plainly: "Likely resolved by restarting the failed job — no code change
   needed." Otherwise reference the piece to change (Makefile target,
   `resources/*.json` tool entry, composer dependency) without pretending to
   design the change.

## Rules

- Use only the attached log and files in this repository. Do not use web
  access or fetch anything over the network.
- Do not modify, create, or delete any files.
- Quote real log lines only; never invent versions, URLs, or checksums.
- If evidence is skimpy, say so and give low confidence.
- Output is pasted verbatim into the run summary; keep it valid markdown,
  concise — a few lines per failed job.
- Per failed job, output exactly:

  **Diagnosis** (with the cause tag)
  **Evidence**
  **Suggested resolution**
  **Confidence** (high/medium/low)
