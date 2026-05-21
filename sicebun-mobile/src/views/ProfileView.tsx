import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { authService } from '../services/api';
import { User, Key, ShieldAlert, LogOut, Camera, Save, CheckCircle2, ChevronRight, X } from 'lucide-react';


const resolvePhoto = (photoPath?: string) => {
  if (!photoPath) return 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150';
  if (photoPath.startsWith('http') || photoPath.startsWith('data:')) return photoPath;
  const clean = photoPath.replace(/^\.\/assets\/uploads\//, '').replace(/^assets\/uploads\//, '');
  return `/assets/uploads/users/${clean}`;
};

export const ProfileView: React.FC = () => {
  const { user, logout, updateProfile, refreshDashboard } = useApp();
  const [loading, setLoading] = useState(false);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  // Profile Edit fields
  const [name, setName] = useState(user?.name || '');
  const [address, setAddress] = useState(user?.address || '');
  const [phone, setPhone] = useState(user?.phone || '');
  const [email, setEmail] = useState(user?.email || '');

  // PIN Form modal state
  const [showPinModal, setShowPinModal] = useState(false);
  const [oldPin, setOldPin] = useState('');
  const [newPin, setNewPin] = useState('');
  const [confirmPin, setConfirmPin] = useState('');
  const [pinSubmitting, setPinSubmitting] = useState(false);

  // Avatar Upload status
  const [uploadingAvatar, setUploadingAvatar] = useState(false);

  const handleUpdateProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setSuccessMsg(null);
    setErrorMsg(null);

    const success = await updateProfile(name, address, phone, email);
    if (success) {
      setSuccessMsg('Profil berhasil diperbarui!');
      setTimeout(() => setSuccessMsg(null), 3000);
    } else {
      setErrorMsg('Gagal memperbarui profil. Silakan coba lagi.');
    }
    setLoading(false);
  };

  const handlePhotoUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0] && user) {
      const file = e.target.files[0];
      setUploadingAvatar(true);
      setErrorMsg(null);
      
      try {
        const response = await authService.changePhoto(user.id_user, file);
        if (response.status && response.data) {
          // Sync new user object containing updated photo name to localstorage and AppState
          localStorage.setItem('sicebun_user', JSON.stringify({ ...user, photo: response.data.photo }));
          // Quick refresh to reflect photo on dashboard header
          await refreshDashboard();
          setSuccessMsg('Foto profil berhasil diunggah!');
          setTimeout(() => window.location.reload(), 1000);
        } else {
          setErrorMsg(response.msg || 'Gagal mengunggah foto profil.');
        }
      } catch (err: any) {
        setErrorMsg(err.response?.data?.msg || 'Gagal mengunggah foto.');
      } finally {
        setUploadingAvatar(false);
      }
    }
  };

  const handleChangePin = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    setPinSubmitting(true);
    setErrorMsg(null);

    if (newPin !== confirmPin) {
      setErrorMsg('Konfirmasi PIN baru tidak sesuai.');
      setPinSubmitting(false);
      return;
    }

    try {
      const response = await authService.changePassword(user.id_user, oldPin, newPin, confirmPin);
      if (response.status) {
        setShowPinModal(false);
        setOldPin('');
        setNewPin('');
        setConfirmPin('');
        setSuccessMsg('PIN keamanan berhasil diganti!');
        setTimeout(() => setSuccessMsg(null), 3000);
      } else {
        setErrorMsg(response.msg || 'Gagal mengubah PIN. Periksa sandi lama Anda.');
      }
    } catch (err: any) {
      setErrorMsg(err.response?.data?.msg || 'Gagal mengubah PIN keamanan.');
    } finally {
      setPinSubmitting(false);
    }
  };

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '100px' }}>
      {/* Header */}
      <div style={{ marginBottom: '24px' }}>
        <h1 className="header-title" style={{ fontSize: '20px' }}>Profil Petugas</h1>
        <p className="subheader">Kelola informasi pribadi dan pengaturan keamanan Anda</p>
      </div>

      {successMsg && (
        <div className="alert alert-success animate-fade-in">
          <CheckCircle2 size={18} style={{ flexShrink: 0 }} />
          <span>{successMsg}</span>
        </div>
      )}

      {errorMsg && (
        <div className="alert alert-danger animate-fade-in">
          <ShieldAlert size={18} style={{ flexShrink: 0 }} />
          <span>{errorMsg}</span>
        </div>
      )}

      {/* Avatar Card */}
      <div className="card card-glow" style={{ padding: '24px', display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: '24px' }}>
        <div style={{ position: 'relative' }}>
          <img
            src={resolvePhoto(user?.photo)}
            alt={user?.name}
            style={{
              width: '100px',
              height: '100px',
              borderRadius: '50%',
              objectFit: 'cover',
              border: '3px solid var(--primary)',
              boxShadow: '0 8px 25px -5px hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.5)'
            }}
            onError={(e) => {
              (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150';
            }}
          />
          
          <label style={{
            position: 'absolute',
            bottom: '0',
            right: '0',
            background: 'var(--primary)',
            color: 'white',
            borderRadius: '50%',
            width: '32px',
            height: '32px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer',
            border: '2px solid var(--bg)',
            boxShadow: 'var(--shadow)'
          }}>
            <Camera size={14} />
            <input
              type="file"
              accept="image/*"
              onChange={handlePhotoUpload}
              style={{ display: 'none' }}
              disabled={uploadingAvatar}
            />
          </label>
        </div>

        <h3 style={{ fontSize: '18px', fontWeight: '700', color: 'var(--text-main)', marginTop: '16px' }}>{user?.name}</h3>
        <span className="badge badge-ib1" style={{ fontSize: '10px', marginTop: '6px', textTransform: 'capitalize' }}>
          Petugas {user?.peran || 'Inseminator'}
        </span>
      </div>

      {/* Profile Form */}
      <form onSubmit={handleUpdateProfile} className="card" style={{ padding: '20px', marginBottom: '20px' }}>
        <h4 style={{ fontSize: '14px', fontWeight: '600', color: 'var(--text-main)', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <User size={15} style={{ color: 'var(--primary)' }} /> Informasi Akun
        </h4>

        <div className="form-group">
          <label className="form-label">Nama Lengkap</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
            disabled={loading}
          />
        </div>

        <div className="form-group">
          <label className="form-label">Email Petugas</label>
          <input
            type="email"
            className="form-control"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            disabled={loading}
          />
        </div>

        <div className="form-group">
          <label className="form-label">Nomor Telepon / WA</label>
          <input
            type="tel"
            className="form-control"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            required
            disabled={loading}
          />
        </div>

        <div className="form-group" style={{ marginBottom: '24px' }}>
          <label className="form-label">Alamat Lengkap</label>
          <input
            type="text"
            className="form-control"
            value={address}
            onChange={(e) => setAddress(e.target.value)}
            required
            disabled={loading}
          />
        </div>

        <button type="submit" className="btn btn-primary" disabled={loading}>
          {loading ? (
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <span className="shimmer" style={{ width: '16px', height: '16px', borderRadius: '50%' }}></span>
              Menyimpan...
            </span>
          ) : (
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Save size={16} /> Perbarui Profil
            </span>
          )}
        </button>
      </form>

      {/* Security Actions Card */}
      <div className="card" style={{ padding: '20px', marginBottom: '24px' }}>
        <h4 style={{ fontSize: '14px', fontWeight: '600', color: 'var(--text-main)', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Key size={15} style={{ color: 'var(--accent)' }} /> Keamanan Sistem
        </h4>
        
        <button
          type="button"
          className="btn btn-secondary"
          onClick={() => setShowPinModal(true)}
          style={{ justifyContent: 'space-between', padding: '0 20px' }}
        >
          <span style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
            <Key size={16} style={{ color: 'var(--accent)' }} /> Ganti PIN Keamanan
          </span>
          <ChevronRight size={16} style={{ color: 'var(--text-muted)' }} />
        </button>
      </div>

      {/* Logout Action Button */}
      <button onClick={logout} className="btn btn-danger" style={{ background: 'rgba(239, 68, 68, 0.1)', color: 'var(--danger)', border: '1px solid rgba(239, 68, 68, 0.2)' }}>
        <LogOut size={16} /> Keluar dari Aplikasi
      </button>

      {/* GANTI PIN MODAL */}
      {showPinModal && (
        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          background: 'rgba(7, 9, 13, 0.85)',
          backdropFilter: 'blur(10px)',
          WebkitBackdropFilter: 'blur(10px)',
          zIndex: 200,
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'flex-end'
        }} className="animate-fade-in">
          <form onSubmit={handleChangePin} className="card card-glow animate-fade-in" style={{
            width: '100%',
            maxWidth: '450px',
            borderBottomLeftRadius: 0,
            borderBottomRightRadius: 0,
            borderTopLeftRadius: '24px',
            borderTopRightRadius: '24px',
            margin: 0,
            padding: '24px 20px',
            background: 'var(--bg-gradient)'
          }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
              <div>
                <h3 className="header-title" style={{ fontSize: '16px' }}>Ganti PIN Keamanan</h3>
                <p className="subheader">Perbarui kode PIN masuk aplikasi Anda</p>
              </div>
              <button
                type="button"
                onClick={() => setShowPinModal(false)}
                style={{
                  background: 'rgba(255, 255, 255, 0.05)',
                  border: 'none',
                  color: 'var(--text-main)',
                  width: '32px',
                  height: '32px',
                  borderRadius: '50%',
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center'
                }}
              >
                <X size={16} />
              </button>
            </div>

            <div className="form-group">
              <label className="form-label">PIN Keamanan Lama *</label>
              <input
                type="password"
                className="form-control"
                placeholder="••••••"
                value={oldPin}
                onChange={(e) => setOldPin(e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">PIN Keamanan Baru *</label>
              <input
                type="password"
                className="form-control"
                placeholder="Minimal 4 digit"
                value={newPin}
                onChange={(e) => setNewPin(e.target.value)}
                required
              />
            </div>

            <div className="form-group" style={{ marginBottom: '24px' }}>
              <label className="form-label">Konfirmasi PIN Baru *</label>
              <input
                type="password"
                className="form-control"
                placeholder="Ulangi PIN baru"
                value={confirmPin}
                onChange={(e) => setConfirmPin(e.target.value)}
                required
              />
            </div>

            <button type="submit" className="btn btn-primary" disabled={pinSubmitting}>
              {pinSubmitting ? (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <span className="shimmer" style={{ width: '16px', height: '16px', borderRadius: '50%' }}></span>
                  Mengubah...
                </span>
              ) : (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <Save size={16} /> Ubah PIN
                </span>
              )}
            </button>
          </form>
        </div>
      )}
    </div>
  );
};
