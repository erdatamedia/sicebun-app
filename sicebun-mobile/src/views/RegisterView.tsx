import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { UserPlus, Mail, Key, User, Phone, MapPin, Camera, ArrowLeft, ShieldAlert } from 'lucide-react';

export const RegisterView: React.FC = () => {
  const { register, error, loading, navigateTo } = useApp();
  const [name, setName] = useState('');
  const [address, setAddress] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [pin, setPin] = useState('');
  const [photo, setPhoto] = useState<File | undefined>(undefined);
  const [photoPreview, setPhotoPreview] = useState<string | null>(null);
  const [localError, setLocalError] = useState<string | null>(null);

  const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setPhoto(file);
      setPhotoPreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError(null);

    if (!name || !address || !phone || !email || !pin) {
      setLocalError('Mohon isi semua bidang yang wajib.');
      return;
    }

    if (pin.length < 4) {
      setLocalError('PIN harus memiliki panjang minimal 4 karakter.');
      return;
    }

    const success = await register(name, address, phone, email, pin, photo);
    if (!success) {
      // Error is stored in context
    }
  };

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '40px' }}>
      <div style={{ display: 'flex', alignItems: 'center', marginBottom: '24px', gap: '12px' }}>
        <button
          onClick={() => navigateTo('login')}
          style={{
            background: 'rgba(255, 255, 255, 0.05)',
            border: '1px solid rgba(255, 255, 255, 0.08)',
            color: 'var(--text-main)',
            borderRadius: '50%',
            width: '40px',
            height: '40px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer'
          }}
        >
          <ArrowLeft size={18} />
        </button>
        <div>
          <h1 className="header-title" style={{ fontSize: '20px' }}>Daftar Petugas</h1>
          <p className="subheader">Bergabunglah di ekosistem digital SICEBUN</p>
        </div>
      </div>

      {(localError || error) && (
        <div className="alert alert-danger animate-fade-in">
          <ShieldAlert size={18} style={{ flexShrink: 0 }} />
          <span>{localError || error}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="card card-glow" style={{ padding: '20px' }}>
        {/* Photo Upload Avatar Box */}
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: '24px' }}>
          <div style={{ position: 'relative' }}>
            <div style={{
              width: '88px',
              height: '88px',
              borderRadius: '50%',
              border: '2px dashed var(--primary)',
              background: photoPreview ? `url(${photoPreview}) center/cover no-repeat` : 'rgba(16, 22, 31, 0.5)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              overflow: 'hidden'
            }}>
              {!photoPreview && <User size={36} style={{ color: 'var(--text-muted)' }} />}
            </div>
            <label style={{
              position: 'absolute',
              bottom: '-4px',
              right: '-4px',
              background: 'var(--primary)',
              color: 'white',
              borderRadius: '50%',
              width: '28px',
              height: '28px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              border: '2px solid var(--bg)'
            }}>
              <Camera size={14} />
              <input
                type="file"
                accept="image/*"
                onChange={handlePhotoChange}
                style={{ display: 'none' }}
              />
            </label>
          </div>
          <span style={{ fontSize: '11px', color: 'var(--text-muted)', marginTop: '8px' }}>Foto Profil (Opsional)</span>
        </div>

        <div className="form-group">
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <User size={15} /> Nama Lengkap *
            </span>
          </label>
          <input
            type="text"
            className="form-control"
            placeholder="John Doe"
            value={name}
            onChange={(e) => setName(e.target.value)}
            disabled={loading}
            required
          />
        </div>

        <div className="form-group">
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <MapPin size={15} /> Alamat Petugas *
            </span>
          </label>
          <input
            type="text"
            className="form-control"
            placeholder="Jl. Bojongbata Raya No.10"
            value={address}
            onChange={(e) => setAddress(e.target.value)}
            disabled={loading}
            required
          />
        </div>

        <div className="form-group">
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Phone size={15} /> Nomor Telepon / WA *
            </span>
          </label>
          <input
            type="tel"
            className="form-control"
            placeholder="08123456789"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            disabled={loading}
            required
          />
        </div>

        <div className="form-group">
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Mail size={15} /> Alamat Email *
            </span>
          </label>
          <input
            type="email"
            className="form-control"
            placeholder="john@example.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            disabled={loading}
            required
          />
        </div>

        <div className="form-group" style={{ marginBottom: '24px' }}>
          <label className="form-label">
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Key size={15} /> PIN Keamanan *
            </span>
          </label>
          <input
            type="password"
            className="form-control"
            placeholder="Minimal 4 digit angka/pin"
            value={pin}
            onChange={(e) => setPin(e.target.value)}
            disabled={loading}
            required
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
              Memproses Pendaftaran...
            </span>
          ) : (
            <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <UserPlus size={18} /> Daftarkan Akun
            </span>
          )}
        </button>
      </form>
    </div>
  );
};
