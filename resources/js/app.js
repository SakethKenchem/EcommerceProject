import './bootstrap';
import 'preline';
document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoinit();
})

import Alpine from 'alpinejs'
 
window.Alpine = Alpine
 
Alpine.start()
