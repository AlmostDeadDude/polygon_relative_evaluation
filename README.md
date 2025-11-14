## Polygon Evaluation Research Project

This application originated as a university-led crowdsourcing effort to judge the quality of automatically generated tree canopy polygons. Workers on platforms like Microworkers received batches of three selections and ranked them from best to worst. The production system handled:

-   **Task distribution & persistence:** assigning the next available job, saving the rankings in `results/`, and storing campaign/worker metadata in `user_info/` for later aggregation.
-   **Quality control:** requiring a short qualification task before granting access and mixing in a hidden control polygon inside every batch. Failing the control sent the worker to `failed.php` and recycled the job.
-   **Payout verification:** issuing a unique proof code once all tasks plus the control were completed so contributors could claim payment on the crowdsourcing platform.

## Portfolio Demo Adaptations

To showcase the UX safely in a public portfolio, the project now runs in a read-only mode:

-   The backend simply picks a random job file on each visit and never writes to disk (the original storage folders remain untouched).
-   Qualification and hidden-control checks are removed; their purpose is explained on `qualification.php` and `failed.php` for historical context.
-   `about.php` and `results.php` were expanded to describe how the full pipeline operated (quality gates, proof code payouts, large-scale campaigns) while clarifying that the demo only preserves the interactive experience.

The ranking interface, canvas rendering, and navigation flow remain authentic, letting viewers understand the original worker experience without exposing research data or infrastructure.
