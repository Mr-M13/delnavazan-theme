#!/usr/bin/env sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
theme_dir="$repo_dir/theme"

node "$repo_dir/tests/static/validate-theme.mjs"
node --check "$theme_dir/assets/js/navigation.js"

if command -v php >/dev/null 2>&1; then
  find "$theme_dir" -type f -name '*.php' -print0 | xargs -0 -n1 php -l
else
  echo 'PHP unavailable: php -l is deferred to the mandatory staging gate.'
fi

echo 'Theme checks completed.'
