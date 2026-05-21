import React from 'react';
import { AppProvider, useApp } from './context/AppContext';
import { MobileFrame } from './components/MobileFrame';
import { BottomNav } from './components/BottomNav';

// Views
import { LoginView } from './views/LoginView';
import { RegisterView } from './views/RegisterView';
import { DashboardView } from './views/DashboardView';
import { SapiListView } from './views/SapiListView';
import { SapiDetailView } from './views/SapiDetailView';
import { AddSapiView } from './views/AddSapiView';
import { AlarmView } from './views/AlarmView';
import { ProfileView } from './views/ProfileView';

const AppContent: React.FC = () => {
  const { activeView } = useApp();

  const renderActiveView = () => {
    switch (activeView) {
      case 'login':
        return <LoginView />;
      case 'register':
        return <RegisterView />;
      case 'dashboard':
        return <DashboardView />;
      case 'sapi-list':
        return <SapiListView />;
      case 'sapi-detail':
        return <SapiDetailView />;
      case 'add-sapi':
        return <AddSapiView />;
      case 'alarm':
        return <AlarmView />;
      case 'profile':
        return <ProfileView />;
      default:
        return <LoginView />;
    }
  };

  return (
    <MobileFrame>
      {renderActiveView()}
      <BottomNav />
    </MobileFrame>
  );
};

function App() {
  return (
    <AppProvider>
      <AppContent />
    </AppProvider>
  );
}

export default App;
