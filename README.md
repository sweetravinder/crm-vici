# ViciCRM Enterprise

This repository contains the full build system for the ViciCRM project.
The folder structure is:

- `vicicrm/` — CRM source files
- `.github/workflows/` — GitHub Actions CI/CD
- `build_vicicrm.sh` — Build script (ZIP + TAR.GZ)

Build Steps:
1. Upload CRM files into `vicicrm/`
2. Push to GitHub
3. Go to Actions → Run workflow
4. Download generated ZIP and TAR.GZ from Releases
