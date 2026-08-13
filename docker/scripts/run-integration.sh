#!/usr/bin/env bash
set -euo pipefail

# Runs the integration suite against every Tile38 version listed in TILE38_VERSIONS.
# Each entry is "service:version", where service is the docker-compose service name
# (which resolves to the container's Tile38 instance) and version is the release tag.

if [[ -z "${TILE38_VERSIONS:-}" ]]; then
    echo "TILE38_VERSIONS is not set" >&2
    exit 1
fi

failures=0

for pair in ${TILE38_VERSIONS}; do
    host="${pair%%:*}"
    version="${pair##*:}"

    echo
    echo "================================================================"
    echo "Tile38 ${version} (${host})"
    echo "================================================================"

    if ! TILE38_HOST="${host}" TILE38_PORT=9851 TILE38_VERSION="${version}" \
        vendor/bin/phpunit --configuration phpunit-integration.xml; then
        failures=$((failures + 1))
    fi
done

echo
if [[ ${failures} -gt 0 ]]; then
    echo "Integration failures across ${failures} version(s)" >&2
    exit 1
fi

echo "All integration tests passed across every Tile38 version."
