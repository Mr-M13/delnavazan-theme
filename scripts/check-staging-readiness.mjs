#!/usr/bin/env node

import fs from 'node:fs';
import path from 'node:path';
import process from 'node:process';

const manifestPath = process.argv[2];

if (!manifestPath) {
  console.error('Usage: node scripts/check-staging-readiness.mjs <manifest.json>');
  process.exit(2);
}

let manifest;
try {
  manifest = JSON.parse(fs.readFileSync(path.resolve(manifestPath), 'utf8'));
} catch (error) {
  console.error(`Unable to read staging manifest: ${error.message}`);
  process.exit(2);
}

const failures = [];
const requiredTrue = [
  ['authorisation.staging_activation_authorised', manifest.authorisation?.staging_activation_authorised],
  ['environment.access_controlled', manifest.environment?.access_controlled],
  ['environment.database_separate', manifest.environment?.database_separate],
  ['environment.uploads_separate', manifest.environment?.uploads_separate],
  ['environment.cache_namespace_separate', manifest.environment?.cache_namespace_separate],
  ['indexing.authentication_required', manifest.indexing?.authentication_required],
  ['indexing.noindex_enabled', manifest.indexing?.noindex_enabled],
  ['side_effects.mail_sink_enabled', manifest.side_effects?.mail_sink_enabled],
  ['side_effects.whatsapp_disabled', manifest.side_effects?.whatsapp_disabled],
  ['side_effects.outbound_webhooks_blocked', manifest.side_effects?.outbound_webhooks_blocked],
  ['side_effects.production_webhook_callbacks_blocked', manifest.side_effects?.production_webhook_callbacks_blocked],
  ['side_effects.production_payment_callbacks_blocked', manifest.side_effects?.production_payment_callbacks_blocked],
  ['side_effects.amelia_external_side_effects_neutralised', manifest.side_effects?.amelia_external_side_effects_neutralised],
  ['side_effects.google_calendar_meet_disabled_or_isolated', manifest.side_effects?.google_calendar_meet_disabled_or_isolated],
  ['side_effects.production_cron_effects_blocked', manifest.side_effects?.production_cron_effects_blocked],
  ['side_effects.production_analytics_suppressed', manifest.side_effects?.production_analytics_suppressed],
  ['side_effects.production_integrations_sandboxed_or_absent', manifest.side_effects?.production_integrations_sandboxed_or_absent],
  ['data_safety.no_live_customer_data', manifest.data_safety?.no_live_customer_data],
  ['data_safety.no_live_booking_data', manifest.data_safety?.no_live_booking_data],
  ['data_safety.no_live_payment_data', manifest.data_safety?.no_live_payment_data],
  ['data_safety.no_live_teacher_private_data', manifest.data_safety?.no_live_teacher_private_data],
  ['data_safety.no_live_contact_pii', manifest.data_safety?.no_live_contact_pii],
  ['data_safety.no_production_database_writes_possible', manifest.data_safety?.no_production_database_writes_possible],
  ['rollback.customizer_exported', manifest.rollback?.customizer_exported],
  ['rollback.menus_widgets_recorded', manifest.rollback?.menus_widgets_recorded],
  ['rollback.plugin_state_recorded', manifest.rollback?.plugin_state_recorded],
  ['rollback.neve_baseline_captured', manifest.rollback?.neve_baseline_captured],
  ['rollback.rank_math_baseline_captured', manifest.rollback?.rank_math_baseline_captured],
  ['rollback.rollback_tested', manifest.rollback?.rollback_tested],
  ['rollback.reset_tested', manifest.rollback?.reset_tested],
  ['rollback.urls_and_seo_unchanged_proven', manifest.rollback?.urls_and_seo_unchanged_proven],
];

for (const [name, value] of requiredTrue) {
  if (value !== true) failures.push(`${name} must be true`);
}

const requiredStrings = [
  ['candidate.repository', manifest.candidate?.repository],
  ['candidate.branch', manifest.candidate?.branch],
  ['authorisation.authorisation_reference', manifest.authorisation?.authorisation_reference],
  ['environment.hostname', manifest.environment?.hostname],
  ['environment.php_version', manifest.environment?.php_version],
  ['environment.wordpress_version', manifest.environment?.wordpress_version],
  ['rollback.snapshot_reference', manifest.rollback?.snapshot_reference],
  ['plugins.rank_math', manifest.plugins?.rank_math],
  ['plugins.otter', manifest.plugins?.otter],
  ['plugins.advanced_post_block', manifest.plugins?.advanced_post_block],
  ['plugins.delnavazan_enhancements', manifest.plugins?.delnavazan_enhancements],
  ['plugins.amelia', manifest.plugins?.amelia],
];

for (const [name, value] of requiredStrings) {
  if (typeof value !== 'string' || value.trim() === '') {
    failures.push(`${name} must be a non-empty string`);
  }
}

if (manifest.schema_version !== 1) failures.push('schema_version must be 1');
if (manifest.candidate?.repository !== 'Mr-M13/delnavazan-theme') {
  failures.push('candidate.repository must be Mr-M13/delnavazan-theme');
}
if (!/^[0-9a-f]{40}$/i.test(manifest.candidate?.source_commit ?? '')) {
  failures.push('candidate.source_commit must be a 40-character commit SHA');
}
if (manifest.candidate?.theme_version !== '0.2.0') {
  failures.push('candidate.theme_version must remain 0.2.0 for this validation increment');
}
if (!/^[0-9a-f]{64}$/i.test(manifest.candidate?.package_sha256 ?? '')) {
  failures.push('candidate.package_sha256 must be a SHA-256 value');
}
if (manifest.environment?.wp_environment_type !== 'staging') {
  failures.push('environment.wp_environment_type must be staging');
}

const hostname = (manifest.environment?.hostname ?? '').trim().toLowerCase();
if (hostname === 'delnavazan.com' || hostname === 'www.delnavazan.com') {
  failures.push('environment.hostname must not be the production hostname');
}
if (manifest.rollback?.neve_version !== '4.2.3') {
  failures.push('rollback.neve_version must preserve the observed Neve 4.2.3 baseline');
}

const requiredSurfaces = [
  'homepage',
  'articles-index',
  'long-form-article',
  'enrolment',
  'teacher',
  'course',
  'regional-course',
  'booking-received',
];
const surfaces = new Map(
  (manifest.regression?.surfaces ?? []).map((surface) => [surface.key, surface]),
);
for (const requiredSurface of requiredSurfaces) {
  const surface = surfaces.get(requiredSurface);
  if (!surface) {
    failures.push(`regression.surfaces must include ${requiredSurface}`);
    continue;
  }
  if (typeof surface.path !== 'string' || !surface.path.startsWith('/')) {
    failures.push(`regression surface ${requiredSurface} must have a verified root-relative path`);
  }
}

const widths = new Set(manifest.regression?.viewport_widths ?? []);
for (const width of [320, 375, 390, 768, 1348]) {
  if (!widths.has(width)) failures.push(`regression.viewport_widths must include ${width}`);
}

const secretName = /(?:password|passwd|secret|token|api[_-]?key|private[_-]?key|credential)/i;
function inspectKeys(value, trail = []) {
  if (!value || typeof value !== 'object') return;
  for (const [key, nested] of Object.entries(value)) {
    const nextTrail = [...trail, key];
    if (secretName.test(key)) {
      failures.push(`secret-like field is prohibited: ${nextTrail.join('.')}`);
    }
    inspectKeys(nested, nextTrail);
  }
}
inspectKeys(manifest);

if (failures.length > 0) {
  console.error('Staging readiness gate FAILED:');
  for (const failure of failures) console.error(`- ${failure}`);
  process.exit(1);
}

console.log('Staging readiness gate passed. Verify every assertion independently before activation.');
