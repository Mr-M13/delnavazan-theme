#!/usr/bin/env sh
set -eu

repo_dir=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
theme_dir="$repo_dir/theme"

node "$repo_dir/tests/static/validate-theme.mjs"
node "$repo_dir/tests/static/portal-dialog.mjs"
node --check "$theme_dir/assets/js/navigation.js"
node --check "$theme_dir/assets/js/portal.js"

if command -v php >/dev/null 2>&1; then
	find "$theme_dir" -type f -name '*.php' -print0 | xargs -0 -n1 php -l
	php "$repo_dir/tests/render/portal-corrections.php"
else
  echo 'PHP unavailable: php -l is deferred to the mandatory staging gate.'
fi

echo 'Theme checks completed.'
