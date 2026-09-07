#!/usr/bin/env sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
theme_dir="$repo_dir/theme"
dist_dir="$repo_dir/dist"
package_name=delnavazan-production-theme
version=$(sed -n 's/^Version: //p' "$theme_dir/style.css")

if [ -z "$version" ]; then
  echo 'Unable to read theme version.' >&2
  exit 1
fi

"$repo_dir/scripts/check-theme.sh"

build_dir=$(mktemp -d)
trap 'rm -rf "$build_dir"' EXIT INT TERM

mkdir -p "$dist_dir" "$build_dir/$package_name"
cp -R "$theme_dir/." "$build_dir/$package_name/"

(
  cd "$build_dir"
  zip -qr "$dist_dir/$package_name-$version.zip" "$package_name"
)

sha256sum "$dist_dir/$package_name-$version.zip" > "$dist_dir/$package_name-$version.sha256"
unzip -tqq "$dist_dir/$package_name-$version.zip"

echo "Built $dist_dir/$package_name-$version.zip"
