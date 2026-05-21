// Minimal Service Worker to enable Chrome PWA installation
const CACHE_NAME = 'sicebun-v1';

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  // Pass-through network request without heavy caching to ensure the app is always up-to-date
  event.respondWith(fetch(event.request));
});
