const permissions = document.querySelectorAll('[data-permissions]');

const handleBadge = (badge, toggles) => {
  let checkedToggles = 0;

  toggles.forEach((toggle) => {
    checkedToggles += toggle.checked ? 1 : 0;
  });

  badge.innerText = `${checkedToggles}/${toggles.length}`;

  if (checkedToggles === 0) {
    badge.classList.remove('teal');

    return;
  }

  badge.classList.add('teal');
};

const handleToggles = (toggles, value) => {
  toggles.forEach((toggle) => {
    toggle.checked = value;
  });
};

const handleToggleAll = (toggleAll, toggles) => {
  let isToggled = true;

  toggles.forEach((toggle) => {
    if (!toggle.checked) {
      isToggled = false;

      return;
    }
  });

  toggleAll.checked = isToggled;
};

permissions?.forEach((perm) => {
  const badge = perm.querySelector('[data-badge]');
  const toggles = perm.querySelectorAll('[data-toggles] input');
  const toggleAll = perm.querySelector('[data-toggle-all]');

  handleBadge(badge, toggles);
  handleToggleAll(toggleAll, toggles);

  toggleAll.addEventListener('change', () => {
    handleToggles(toggles, toggleAll.checked);
    handleBadge(badge, toggles);
  });

  toggles.forEach((toggle) => {
    toggle.addEventListener('change', () => {
      handleBadge(badge, toggles);
      handleToggleAll(toggleAll, toggles);
    });
  });
});
