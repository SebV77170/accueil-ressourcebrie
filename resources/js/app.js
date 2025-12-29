import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
  Alpine.store('ui', {
    showEdit: false,
    showDelete: false,
    showAddCategory: false,
  })

  Alpine.store('site', {
    id: null,
    nom: '',
    url: '',
    categorie: '',
    description: '',
  })
})

Alpine.start();
