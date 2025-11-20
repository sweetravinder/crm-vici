#!/bin/bash
set -euo pipefail

echo "==============================="
echo "     ViciCRM Build System"
echo "==============================="

REPO_ROOT="$GITHUB_WORKSPACE"
BUILD_DIR="/tmp/vicicrm_build_$$"
PKG_PREFIX="vicicrm_enterprise_full"
TS=$(date -u +'%Y%m%dT%H%M%SZ"
PKG_NAME="${PKG_PREFIX}_${TS}"

echo "Creating build directory..."
mkdir -p "$BUILD_DIR"

echo "Copying vicicrm/ folder to build directory..."
cp -r "$REPO_ROOT/vicicrm" "$BUILD_DIR/vicicrm"

if [ ! -d "$BUILD_DIR/vicicrm" ]; then
    echo "ERROR: vicicrm folder missing!"; exit 1
fi

cd "$BUILD_DIR"

echo "--------------------------------"
echo " Building ZIP Package"
echo "--------------------------------"
zip -r "${PKG_NAME}.zip" vicicrm

echo "--------------------------------"
echo " Building TAR.GZ Package"
echo "--------------------------------"
tar -czf "${PKG_NAME}.tar.gz" vicicrm

echo "--------------------------------"
echo " Generating SHA256"
echo "--------------------------------"
sha256sum "${PKG_NAME}.zip" "${PKG_NAME}.tar.gz" > sha256.txt

echo "--------------------------------"
echo " Moving artifacts"
echo "--------------------------------"
mv "${PKG_NAME}.zip" "${PKG_NAME}.tar.gz" sha256.txt "$REPO_ROOT"

echo "--------------------------------"
echo " BUILD COMPLETE"
echo "--------------------------------"

ls -lh "$REPO_ROOT/${PKG_PREFIX}"*
