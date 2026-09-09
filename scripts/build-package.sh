#!/usr/bin/env sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
theme_dir="$repo_dir/theme"
dist_dir="$repo_dir/dist"
package_name=delnavazan-production-theme
version=$(sed -n 's/^Version: //p' "$theme_dir/style.css")
archive="$dist_dir/$package_name-$version.zip"
checksum="$dist_dir/$package_name-$version.sha256"

if [ -z "$version" ]; then
  echo 'Unable to read theme version.' >&2
  exit 1
fi

"$repo_dir/scripts/check-theme.sh"

if find "$theme_dir" -type l -print -quit | grep -q .; then
  echo 'Theme package must not contain symbolic links.' >&2
  exit 1
fi

build_dir=$(mktemp -d)
trap 'rm -rf "$build_dir"' EXIT INT TERM

mkdir -p "$dist_dir" "$build_dir/$package_name"
cp -R "$theme_dir/." "$build_dir/$package_name/"
rm -f "$archive" "$checksum"

(
  cd "$build_dir"
  zip -qr "$archive" "$package_name"
)

unzip -tqq "$archive"

archive_list=$(unzip -Z1 "$archive")
if printf '%s\n' "$archive_list" | grep -Ev "^$package_name/" | grep -q .; then
  echo 'Package contains an entry outside the single Theme root.' >&2
  exit 1
fi

for required in style.css index.php functions.php theme.json; do
  if ! printf '%s\n' "$archive_list" | grep -Fxq "$package_name/$required"; then
    echo "Package is missing required root file: $required" >&2
    exit 1
  fi
done

if ! unzip -p "$archive" "$package_name/style.css" | grep -Eq "^Version: $version$"; then
  echo 'Packaged Theme version does not match the build version.' >&2
  exit 1
fi

if printf '%s\n' "$archive_list" | grep -Ei '/(\.git|\.svn|node_modules|tests?|scripts?|coverage)(/|$)|/(\.DS_Store|Thumbs\.db|\.env(\.|$)|[^/]*(credential|secret)[^/]*|[^/]*\.(pem|key))$' | grep -q .; then
  echo 'Package contains development junk or a credential-like file.' >&2
  exit 1
fi

if printf '%s\n' "$archive_list" | grep -Fq '/assets/images/hero-strings.svg'; then
  echo 'Package contains the retired hero-strings.svg asset.' >&2
  exit 1
fi

validation_dir="$build_dir/package-inspection"
mkdir -p "$validation_dir"
unzip -q "$archive" -d "$validation_dir"
packaged_theme="$validation_dir/$package_name"

if grep -ERIl --include='*.php' --include='*.css' --include='*.js' --include='*.json' --include='*.svg' --include='*.txt' '(BEGIN (RSA |OPENSSH |EC |DSA )?PRIVATE KEY|AKIA[0-9A-Z]{16}|api[_-]?key[[:space:]]*[:=][[:space:]]*[^[:space:]]+|password[[:space:]]*[:=][[:space:]]*[^[:space:]]+)' "$packaged_theme" | grep -q .; then
  echo 'Package contains credential-like text.' >&2
  exit 1
fi

if grep -ERIl --include='*.php' --include='*.css' --include='*.js' --include='*.json' --include='*.svg' --include='*.txt' '(\+?61[[:space:]().-]*431[[:space:].-]*364[[:space:].-]*200|0431[[:space:].-]*364[[:space:].-]*200|3[[:space:]]*months|سه[[:space:]]+ماه)' "$packaged_theme" | grep -q .; then
  echo 'Package contains prohibited public contact or obsolete term wording.' >&2
  exit 1
fi

sha256sum "$archive" > "$checksum"

echo "Built $archive"
