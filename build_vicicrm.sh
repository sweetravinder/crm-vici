#!/bin/bash
set -euo pipefail

REPO_URL="https://github.com/sweetravinder/crm-vici.git"
BRANCH="main"
TARGET_DIR="/tmp/vicicrm_build_$$"
PKG_PREFIX="vicicrm_enterprise_full"
TS=$(date -u +'%Y%m%dT%H%M%SZ')
PKG_NAME="${PKG_PREFIX}_${TS}"

echo "Cloning the repository..."
git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$TARGET_DIR"

cd "$TARGET_DIR"

echo "Checking directory structure..."
if [ ! -d "vicicrm" ]; then
    echo "ERROR: 'vicicrm' folder not found in repo root"; exit 1
fi

echo "Building tar.gz..."
tar -czf "${PKG_NAME}.tar.gz" vicicrm

echo "Building zip..."
zip -r "${PKG_NAME}.zip" vicicrm

echo "Moving artifacts up..."
mv "${PKG_NAME}.tar.gz" "${PKG_NAME}.zip" ..

echo "Artifacts built:"
ls -lh "../${PKG_NAME}.tar.gz" "../${PKG_NAME}.zip"

echo "Done."
