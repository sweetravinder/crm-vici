#!/bin/bash
set -e

echo "==============================="
echo "     ViciCRM Build System"
echo "==============================="

# Root path
REPO_ROOT="$GITHUB_WORKSPACE"
BUILD_DIR="$REPO_ROOT/vicicrm"
PKG_NAME="vicicrm_enterprise_full_$(date -u +%Y%m%dT%H%M%SZ)"

echo "Building package: $PKG_NAME"
echo "Repository root:  $REPO_ROOT"
echo "Build directory:  $BUILD_DIR"

cd "$REPO_ROOT"

# Ensure build dir exists
if [ ! -d "$BUILD_DIR" ]; then
    echo "ERROR: vicicrm folder NOT found!"
    exit 1
fi

# Create temp build folder
mkdir -p build_temp
cp -R vicicrm/* build_temp/

echo "Creating tar.gz..."
tar -czf "${PKG_NAME}.tar.gz" -C build_temp .

echo "Creating zip..."
cd build_temp
zip -r "../${PKG_NAME}.zip" .
cd ..

echo "Generating SHA256 checksum..."
sha256sum "${PKG_NAME}.zip" "${PKG_NAME}.tar.gz" > sha256.txt

echo "Cleaning up temp folder..."
rm -rf build_temp

echo "Artifacts built:"
ls -lh "${PKG_NAME}.zip" "${PKG_NAME}.tar.gz" sha256.txt

echo "DONE."
