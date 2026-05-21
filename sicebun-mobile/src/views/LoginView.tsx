import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { LogIn, Key, Mail, ShieldAlert } from 'lucide-react';

export const LoginView: React.FC = () => {
  const { login, error, loading, navigateTo } = useApp();
  const [email, setEmail] = useState('');
  const [pin, setPin] = useState('');
  const [localError, setLocalError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError(null);

    if (!email) {
      setLocalError('Email wajib diisi.');
      return;
    }
    if (!pin) {
      setLocalError('PIN / Password wajib diisi.');
      return;
    }

    const success = await login(email, pin);
    if (!success) {
      // Error is set in context
    }
  };

  return (
    <div className="app-viewport animate-fade-in" style={{ justifyContent: 'center', minHeight: '100%' }}>
      <div style={{ textAlign: 'center', marginBottom: '32px' }}>
        {/* Glow emblem wrapper */}
        <div style={{
          display: 'inline-flex',
          padding: '16px',
          borderRadius: '24px',
          background: 'hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.12)',
          border: '1px solid hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.2)',
          boxShadow: '0 0 25px hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.2)',
          marginBottom: '16px'
        }}>
          <LogIn size={40} style={{ color: 'var(--primary)' }} />
        </div>
        <h1 className="header-title" style={{ fontSize: '26px', fontFamily: 'var(--font-title)' }}>SICEBUN</h1>
        <p className="subheader">Sistem Informasi Monitoring & Reproduksi Sapi</p>
      </div>

      {(localError || error) && (
        <div className="alert alert-danger animate-fade-in">
          <ShieldAlert size={18} style={{ flexShrink: 0 }} />
          <span>{localError || error}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="card card-glow" style={{ padding: '24px' }}>
        <div className="form-group">
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Mail size={16} /> Email Inseminator
            </span>
          </label>
          <input
            type="email"
            className="form-control"
            placeholder="nama@email.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            disabled={loading}
          />
        </div>

        <div className="form-group" style={{ marginBottom: '24px' }}>
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Key size={16} /> PIN Keamanan
            </span>
          </label>
          <input
            type="password"
            className="form-control"
            placeholder="••••••"
            value={pin}
            onChange={(e) => setPin(e.target.value)}
            disabled={loading}
          />
        </div>

        <button
          type="submit"
          className="btn btn-primary"
          disabled={loading}
        >
          {loading ? (
            <span style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
              <span className="shimmer" style={{ width: '18px', height: '18px', borderRadius: '50%' }}></span>
              Memproses Masuk...
            </span>
          ) : (
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <LogIn size={18} /> Masuk Aplikasi
            </span>
          )}
        </button>
      </form>

      <div style={{ textAlign: 'center', marginTop: '20px' }}>
        <p style={{ fontSize: '13px', color: 'var(--text-muted)' }}>
          Belum terdaftar sebagai petugas?{' '}
          <button
            onClick={() => navigateTo('register')}
            style={{
              background: 'none',
              border: 'none',
              color: 'var(--primary)',
              fontWeight: '600',
              cursor: 'pointer',
              textDecoration: 'underline'
            }}
          >
            Daftar Sekarang
          </button>
        </p>
      </div>
    </div>
  );
};
