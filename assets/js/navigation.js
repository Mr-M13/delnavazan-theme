(() => {
  'use strict';

  const button = document.querySelector('.menu-toggle');
  const navigation = document.querySelector('#primary-navigation');

  if (!button || !navigation) {
    return;
  }

  button.hidden = false;

  const desktopQuery = window.matchMedia('(min-width: 64rem)');

  const syncNavigation = () => {
    if (desktopQuery.matches) {
      navigation.hidden = false;
      button.setAttribute('aria-expanded', 'false');
      return;
    }

    navigation.hidden = button.getAttribute('aria-expanded') !== 'true';
  };

  button.addEventListener('click', () => {
    const isOpen = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!isOpen));
    navigation.hidden = isOpen;

    if (!isOpen) {
      const firstLink = navigation.querySelector('a');
      if (firstLink) {
        firstLink.focus();
      }
    }
  });

  navigation.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape' || desktopQuery.matches) {
      return;
    }

    button.setAttribute('aria-expanded', 'false');
    navigation.hidden = true;
    button.focus();
  });

  desktopQuery.addEventListener('change', syncNavigation);
  syncNavigation();
})();
