#!/usr/bin/env node
/**
 * Generates docs/src/content/docs/reference/commands.md from the client's
 * CommandRegistry, keeping the docs in lockstep with the actual command surface.
 *
 * Usage: npm run generate:commands  (from docs/)
 */
import { readFileSync, writeFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, resolve } from 'node:path';

const here = dirname(fileURLToPath(import.meta.url));
const registryPath = resolve(here, '../../src/Commands/CommandRegistry.php');
const outputPath = resolve(here, '../src/content/docs/reference/commands.md');

const registry = readFileSync(registryPath, 'utf8');

const commands = {};
const re = /'([a-z0-9]+)'\s*=>\s*([A-Za-z0-9\\]+)::class,/g;
let match;
while ((match = re.exec(registry)) !== null) {
  commands[match[1]] = match[2];
}

const since = {
  chans: '1.13.0', delchan: '1.13.0', pdelchan: '1.13.0', psubscribe: '1.13.0',
  setchan: '1.13.0', subscribe: '1.13.0',
  auth: '1.0.0', output: '1.1.0', ping: '1.0.0', quit: '1.0.0',
  bounds: '1.3.0', del: '1.0.0', drop: '1.0.0', exists: '1.33.0', expire: '1.0.0',
  fexists: '1.33.0', fget: '1.33.0', fset: '1.0.0', get: '1.0.0', jdel: '1.7.0',
  jget: '1.7.0', jset: '1.7.0', keys: '1.0.0', pdel: '1.7.0', persist: '1.0.0',
  rename: '1.14.5', renamenx: '1.14.5', set: '1.0.0', stats: '1.0.0', ttl: '1.0.0',
  aof: '1.0.0', aofmd5: '1.0.0', aofshrink: '1.0.0', follow: '1.0.0',
  eval: '1.10.0', evalna: '1.10.0', evalnasha: '1.10.0', evalro: '1.10.0',
  evalrosha: '1.10.0', evalsha: '1.10.0', script: '1.10.0',
  intersects: '1.0.0', nearby: '1.0.0', scan: '1.0.0', search: '1.4.2', within: '1.0.0',
  config: '1.0.0', flushdb: '1.0.0', gc: '1.0.0', healthz: '1.24.1', info: '1.0.0',
  readonly: '1.0.0', role: '1.32.0', server: '1.0.0',
  delhook: '1.0.0', hooks: '1.0.0', pdelhook: '1.0.0', sethook: '1.0.0',
  test: '1.16.0', raw: '—',
};

const groupOf = (fqcn) => {
  const parts = fqcn.split('\\');
  return parts[parts.length - 2] ?? '—';
};

const groupOrder = [
  'Channel', 'Connection', 'Key', 'Replication', 'Scripting',
  'Search', 'Server', 'Webhook', 'Utility',
];

const rows = Object.entries(commands)
  .map(([name, fqcn]) => ({
    name,
    command: name.toUpperCase(),
    group: groupOf(fqcn),
    fqcn,
    since: since[name] ?? '—',
  }))
  .sort((a, b) => {
    const ga = groupOrder.indexOf(a.group);
    const gb = groupOrder.indexOf(b.group);
    if (ga !== gb) return ga - gb;
    return a.name.localeCompare(b.name);
  });

const table = [
  '| Command | Method | Since | Class |',
  '|---------|--------|-------|-------|',
  ...rows.map(
    (r) =>
      `| \`${r.command}\` | \`$client->${r.name}()\` | ${r.since} | \`${r.fqcn}\` |`,
  ),
].join('\n');

const md = `---
title: Command Reference
description: Every command supported by the client, with its wire keyword, method, minimum Tile38 version, and class.
---

> This page is generated from \`CommandRegistry\` — run \`npm run generate:commands\` in
> \`docs/\` to refresh it after the client changes.

Every command is dispatched by name through the client. The minimum version is the
first Tile38 release the command is known to work with; see
[Version Compatibility](/reference/version-compatibility/) for the full picture.

${table}
`;

writeFileSync(outputPath, md, 'utf8');
console.log(`Generated ${outputPath} with ${rows.length} commands.`);
