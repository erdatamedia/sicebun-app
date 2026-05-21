import React, { createContext, useContext, useState, useEffect, type ReactNode } from 'react';
import type { User, Sapi } from '../types/api';
import { authService, dashboardService, sapiService, alarmService } from '../services/api';
import type { DashboardData } from '../services/api';


interface AppContextType {
  user: User | null;
  activeView: 'login' | 'register' | 'dashboard' | 'sapi-list' | 'sapi-detail' | 'add-sapi' | 'alarm' | 'profile';
  activeTab: 'beranda' | 'sapi' | 'alarm' | 'profil';
  sapiDetailId: string | null;
  dashboardData: DashboardData | null;
  sapiList: Sapi[] | null;
  alarmList: Sapi[] | null;
  loading: boolean;
  error: string | null;
  
  // Actions
  login: (email: string, pin: string) => Promise<boolean>;
  register: (name: string, address: string, phone: string, email: string, pin: string, photo?: File) => Promise<boolean>;
  logout: () => void;
  updateProfile: (name: string, address: string, phone: string, email: string) => Promise<boolean>;
  refreshDashboard: () => Promise<void>;
  refreshSapiList: (status?: string) => Promise<void>;
  refreshAlarms: () => Promise<void>;
  viewSapiDetail: (id: string) => void;
  navigateTo: (view: 'login' | 'register' | 'dashboard' | 'sapi-list' | 'sapi-detail' | 'add-sapi' | 'alarm' | 'profile') => void;
  switchTab: (tab: 'beranda' | 'sapi' | 'alarm' | 'profil') => void;
  setError: (msg: string | null) => void;
  setLoading: (val: boolean) => void;
}

const AppContext = createContext<AppContextType | undefined>(undefined);

export const AppProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [activeView, setActiveView] = useState<AppContextType['activeView']>('login');
  const [activeTab, setActiveTab] = useState<AppContextType['activeTab']>('beranda');
  const [sapiDetailId, setSapiDetailId] = useState<string | null>(null);
  const [dashboardData, setDashboardData] = useState<DashboardData | null>(null);
  const [sapiList, setSapiList] = useState<Sapi[] | null>(null);
  const [alarmList, setAlarmList] = useState<Sapi[] | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  // Initialize: Load session from localStorage
  useEffect(() => {
    const savedUser = localStorage.getItem('sicebun_user');
    if (savedUser) {
      try {
        const parsed = JSON.parse(savedUser) as User;
        setUser(parsed);
        setActiveView('dashboard');
        setActiveTab('beranda');
      } catch (e) {
        localStorage.removeItem('sicebun_user');
      }
    }
  }, []);

  // Sync dashboard, alarms, and sapi lists when user changes
  useEffect(() => {
    if (user) {
      loadInitialData();
    } else {
      setDashboardData(null);
      setSapiList(null);
      setAlarmList(null);
    }
  }, [user]);

  const loadInitialData = async () => {
    if (!user) return;
    setLoading(true);
    try {
      const [dashRes, alarmsRes, sapiRes] = await Promise.all([
        dashboardService.getDashboard(user.id_user),
        alarmService.getAlarms(user.id_user),
        sapiService.getSapiList(user.id_user)
      ]);
      
      if (dashRes.status) setDashboardData(dashRes.data);
      if (alarmsRes.status) setAlarmList(alarmsRes.data);
      if (sapiRes.status) setSapiList(sapiRes.data);
    } catch (e) {
      console.error("Error loading initial data", e);
    } finally {
      setLoading(false);
    }
  };

  const login = async (email: string, pin: string): Promise<boolean> => {
    setLoading(true);
    setError(null);
    try {
      const res = await authService.login(email, pin);
      if (res.status && res.data) {
        setUser(res.data);
        localStorage.setItem('sicebun_user', JSON.stringify(res.data));
        setActiveView('dashboard');
        setActiveTab('beranda');
        return true;
      } else {
        setError(res.msg || 'Email atau PIN salah');
        return false;
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Koneksi gagal ke server.');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const register = async (
    name: string,
    address: string,
    phone: string,
    email: string,
    pin: string,
    photo?: File
  ): Promise<boolean> => {
    setLoading(true);
    setError(null);
    try {
      const res = await authService.register(name, address, phone, email, pin, photo);
      if (res.status) {
        // Automatically login the newly registered user
        const loginRes = await authService.login(email, pin);
        if (loginRes.status && loginRes.data) {
          setUser(loginRes.data);
          localStorage.setItem('sicebun_user', JSON.stringify(loginRes.data));
          setActiveView('dashboard');
          setActiveTab('beranda');
          return true;
        }
        setActiveView('login');
        return true;
      } else {
        setError(res.msg || 'Pendaftaran Gagal');
        return false;
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Gagal mendaftar. Periksa koneksi Anda.');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('sicebun_user');
    setActiveView('login');
    setActiveTab('beranda');
  };

  const updateProfile = async (
    name: string,
    address: string,
    phone: string,
    email: string
  ): Promise<boolean> => {
    if (!user) return false;
    setLoading(true);
    setError(null);
    try {
      const res = await authService.updateProfile(user.id_user, name, address, phone, email);
      if (res.status && res.data) {
        const updatedUser = { ...user, ...res.data };
        setUser(updatedUser);
        localStorage.setItem('sicebun_user', JSON.stringify(updatedUser));
        return true;
      } else {
        setError(res.msg || 'Gagal memperbarui profil');
        return false;
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Gagal terhubung.');
      return false;
    } finally {
      setLoading(false);
    }
  };

  const refreshDashboard = async () => {
    if (!user) return;
    try {
      const res = await dashboardService.getDashboard(user.id_user);
      if (res.status) {
        setDashboardData(res.data);
      }
    } catch (e) {
      console.error("Dashboard refresh failed", e);
    }
  };

  const refreshSapiList = async (status?: string) => {
    if (!user) return;
    try {
      const res = await sapiService.getSapiList(user.id_user, status);
      if (res.status) {
        setSapiList(res.data);
      }
    } catch (e) {
      console.error("Sapi list refresh failed", e);
    }
  };

  const refreshAlarms = async () => {
    if (!user) return;
    try {
      const res = await alarmService.getAlarms(user.id_user);
      if (res.status) {
        setAlarmList(res.data);
      }
    } catch (e) {
      console.error("Alarms refresh failed", e);
    }
  };

  const viewSapiDetail = (id: string) => {
    setSapiDetailId(id);
    setActiveView('sapi-detail');
  };

  const navigateTo = (view: AppContextType['activeView']) => {
    setError(null);
    setActiveView(view);
    
    // Sync active view back to its corresponding tab
    if (view === 'dashboard') setActiveTab('beranda');
    else if (view === 'sapi-list') setActiveTab('sapi');
    else if (view === 'alarm') setActiveTab('alarm');
    else if (view === 'profile') setActiveTab('profil');
  };

  const switchTab = (tab: AppContextType['activeTab']) => {
    setError(null);
    setActiveTab(tab);
    if (tab === 'beranda') setActiveView('dashboard');
    else if (tab === 'sapi') setActiveView('sapi-list');
    else if (tab === 'alarm') setActiveView('alarm');
    else if (tab === 'profil') setActiveView('profile');
  };

  return (
    <AppContext.Provider
      value={{
        user,
        activeView,
        activeTab,
        sapiDetailId,
        dashboardData,
        sapiList,
        alarmList,
        loading,
        error,
        login,
        register,
        logout,
        updateProfile,
        refreshDashboard,
        refreshSapiList,
        refreshAlarms,
        viewSapiDetail,
        navigateTo,
        switchTab,
        setError,
        setLoading,
      }}
    >
      {children}
    </AppContext.Provider>
  );
};

export const useApp = () => {
  const context = useContext(AppContext);
  if (context === undefined) {
    throw new Error('useApp must be used within an AppProvider');
  }
  return context;
};
