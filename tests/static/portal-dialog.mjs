import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import vm from 'node:vm';

const listeners = (target) => {
  target.events = {};
  target.addEventListener = (name, handler) => { target.events[name] = handler; };
  return target;
};
const opener = (id) => listeners({ dataset: { dznDialogOpen: id }, focusCount: 0, focus() { this.focusCount += 1; } });
const closeButton = () => listeners({});
const dialog = (id, native) => {
  const close = closeButton();
  const value = listeners({
    id,
    dataset: {},
    attributes: new Set(),
    closeButton: close,
    querySelector: () => close,
    setAttribute(name) { this.attributes.add(name); },
    removeAttribute(name) { this.attributes.delete(name); },
  });
  if (native) {
    value.showModal = () => { value.shown = true; };
    value.close = () => { value.closed = true; value.events.close?.(); };
  }
  return value;
};

const nativeOpener = opener('native');
const fallbackOpener = opener('fallback');
const nativeDialog = dialog('native', true);
const fallbackDialog = dialog('fallback', false);
const dialogs = { native: nativeDialog, fallback: fallbackDialog };
const document = {
  getElementById: (id) => dialogs[id],
  querySelectorAll(selector) {
    if (selector === '[data-dzn-dialog-open]') return [nativeOpener, fallbackOpener];
    if (selector === '.dzn-portal-dialog') return [nativeDialog, fallbackDialog];
    return [];
  },
};
const source = fs.readFileSync(path.resolve(import.meta.dirname, '../../theme/assets/js/portal.js'), 'utf8');
vm.runInNewContext(source, { document, window: { sessionStorage: {} } });

nativeOpener.events.click();
assert.equal(nativeDialog.shown, true, 'native opener uses showModal');
nativeDialog.closeButton.events.click();
assert.equal(nativeDialog.closed, true, 'native close control closes dialog');
assert.equal(nativeOpener.focusCount, 1, 'native close restores exact opener');

fallbackOpener.events.click();
assert.equal(fallbackDialog.attributes.has('open'), true, 'fallback disclosure becomes visible');
assert.equal(fallbackDialog.dataset.dznDialogFallback, 'disclosure', 'fallback is explicitly non-modal');
assert.equal(fallbackDialog.attributes.has('aria-modal'), false, 'fallback does not claim modal semantics');
assert.equal(fallbackOpener.focusCount, 0, 'fallback opening leaves focus predictably on opener');
fallbackDialog.closeButton.events.click();
assert.equal(fallbackDialog.attributes.has('open'), false, 'fallback close hides disclosure');
assert.equal(fallbackOpener.focusCount, 1, 'fallback close restores exact opener');

console.log('Portal dialog behaviour tests passed.');
