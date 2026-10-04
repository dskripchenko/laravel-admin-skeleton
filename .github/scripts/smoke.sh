#!/usr/bin/env bash
# Smoke-tests a project made by `composer create-project`: what
# post-create-project-cmd left behind, then the running app over HTTP.
# Usage: smoke.sh <project dir>
set -euo pipefail

cd "$1"
fail() { echo "::error::$*"; exit 1; }

# post-create-project-cmd: .env with a key, the SQLite database, migrations,
# the published frontend and the seed.
grep -q '^APP_KEY=base64:' .env || fail 'APP_KEY was not generated'
[ -s database/database.sqlite ] || fail 'database/database.sqlite is missing'
ls public/vendor/admin/assets/*.js > /dev/null || fail 'the admin frontend was not published'
php artisan migrate:status --no-ansi | grep -q 'create_posts_table.*Ran' || fail 'migrations did not run'
[ "$(php artisan tinker --execute 'echo App\Models\Post::count();')" -gt 0 ] || fail 'posts were not seeded'
[ "$(php artisan tinker --execute "echo Dskripchenko\LaravelAdmin\Models\AdminUser::where('email', 'admin@example.com')->count();")" = 1 ] \
  || fail 'the administrator was not seeded'

php artisan serve --port=8000 > serve.log 2>&1 &
trap 'kill %1 2>/dev/null || true' EXIT
for _ in $(seq 1 30); do curl -sf -o /dev/null http://127.0.0.1:8000/up && break; sleep 1; done

base=http://127.0.0.1:8000
jar=$(mktemp)

# The shell and its prebuilt bundle.
html=$(curl -sf -c "$jar" -b "$jar" "$base/admin/login") || fail '/admin/login did not answer 200'
js=$(grep -o '/vendor/admin/assets/[^"]*\.js' <<< "$html" | head -1)
[ -n "$js" ] || fail 'the shell does not reference the admin bundle'
curl -sf -o /dev/null "$base$js" || fail "the bundle $js is not served"

# Sign in as the seeded administrator through the session API, as the SPA does.
xsrf() { python3 -c 'import sys, urllib.parse; print(urllib.parse.unquote(sys.argv[1]))' "$(awk '$6 == "XSRF-TOKEN" { print $7 }' "$jar")"; }
api() {
  curl -sf -c "$jar" -b "$jar" -H 'Accept: application/json' -H 'Content-Type: application/json' \
    -H "X-XSRF-TOKEN: $(xsrf)" "$@"
}
api -d '{"email":"admin@example.com","password":"password"}' "$base/api/admin/auth/login" | grep -q '"success":true' \
  || fail 'the seeded administrator cannot sign in'

# Every menu entry answers.
menu=$(api "$base/api/admin/system/menu")
for slug in posts system-users system-roles system-audit; do
  grep -q "\"resource.$slug\"" <<< "$menu" || fail "the menu has no $slug"
  api -d '{}' "$base/api/admin/$slug/search" | grep -q '"success":true' || fail "$slug/search failed"
done
api "$base/api/admin/dashboard/get?key=main" | grep -q '"success":true' || fail 'the overview dashboard failed'

# The Russian translations of the app's own strings are picked up.
api -d '{"locale":"ru"}' "$base/api/admin/system/setLocale" > /dev/null
api "$base/api/admin/system/menu" | python3 -c 'import json, sys; m = json.dumps(json.load(sys.stdin), ensure_ascii=False); sys.exit(0 if "Посты" in m else 1)' \
  || fail 'lang/ru.json is not applied'

echo 'Smoke test passed.'
