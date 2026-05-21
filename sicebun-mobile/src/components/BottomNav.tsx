import React from 'react';
import { useApp } from '../context/AppContext';
import { Home, ClipboardList, Bell, User } from 'lucide-react';

export const BottomNav: React.FC = () => {
  const { activeTab, switchTab, user } = useApp();

  // Bottom navigation only visible if the user is authenticated
  if (!user) return null;

  return (
    <nav className="bottom-nav">
      <button
        onClick={() => switchTab('beranda')}
        className={`nav-item ${activeTab === 'beranda' ? 'active' : ''}`}
      >
        <Home />
        <span>Beranda</span>
      </button>

      <button
        onClick={() => switchTab('sapi')}
        className={`nav-item ${activeTab === 'sapi' ? 'active' : ''}`}
      >
        <ClipboardList />
        <span>Data Sapi</span>
      </button>

      <button
        onClick={() => switchTab('alarm')}
        className={`nav-item ${activeTab === 'alarm' ? 'active' : ''}`}
      >
        <Bell />
        <span>Alarm</span>
      </button>

      <button
        onClick={() => switchTab('profil')}
        className={`nav-item ${activeTab === 'profil' ? 'active' : ''}`}
      >
        <User />
        <span>Profil</span>
      </button>
    </nav>
  );
};
