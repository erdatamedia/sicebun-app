import React, { useState, useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { ShimmerListItem } from '../components/Shimmer';
import { Search, Plus, User, MapPin, Tag, AlertCircle } from 'lucide-react';
import type { Sapi } from '../types/api';

const resolvePhoto = (photoPath?: string) => {
  if (!photoPath) return 'https://images.unsplash.com/photo-1546445317-29f4545e6d49?w=150';
  if (photoPath.startsWith('http') || photoPath.startsWith('data:')) return photoPath;
  const clean = photoPath.replace(/^\.\/assets\/uploads\//, '').replace(/^assets\/uploads\//, '');
  return `/assets/uploads/${clean}`;
};

export const SapiListView: React.FC = () => {
  const { user, sapiList, loading, navigateTo, viewSapiDetail, refreshSapiList } = useApp();
  const [search, setSearch] = useState('');
  const [selectedFilter, setSelectedFilter] = useState<'all' | 'ib' | 'bunting' | 'tidak_bunting' | 'gangrep' | 'kelahiran'>('all');

  useEffect(() => {
    // Initial fetch of cattle list when mounting
    if (user) {
      refreshSapiList();
    }
  }, [user]);

  // Filter list based on both search query and state filter tabs
  const filteredSapi = sapiList ? sapiList.filter((sapi) => {
    const matchesSearch =
      sapi.no_sapi.toLowerCase().includes(search.toLowerCase()) ||
      sapi.peternak.toLowerCase().includes(search.toLowerCase()) ||
      (sapi.alamat && sapi.alamat.toLowerCase().includes(search.toLowerCase()));

    if (!matchesSearch) return false;

    if (selectedFilter === 'all') return true;
    if (selectedFilter === 'ib') {
      return sapi.status === 'ib1' || sapi.status === 'ib2' || sapi.status === 'ib3';
    }
    return sapi.status === selectedFilter;
  }) : [];

  const getStatusLabel = (status: Sapi['status']) => {
    switch (status) {
      case 'ib1': return 'IB Ke-1';
      case 'ib2': return 'IB Ke-2';
      case 'ib3': return 'IB Ke-3';
      case 'bunting': return 'Bunting';
      case 'tidak_bunting': return 'Tidak Bunting';
      case 'gangrep': return 'Gangrep';
      case 'kelahiran': return 'Melahirkan';
      default: return status;
    }
  };

  const getBadgeClass = (status: Sapi['status']) => {
    return `badge badge-${status}`;
  };

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '100px' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <div>
          <h1 className="header-title" style={{ fontSize: '20px' }}>Daftar Sapi</h1>
          <p className="subheader">Data monitoring reproduksi sapi binaan Anda</p>
        </div>
        <button
          onClick={() => navigateTo('add-sapi')}
          style={{
            background: 'rgba(255, 255, 255, 0.05)',
            border: '1px solid rgba(255, 255, 255, 0.08)',
            color: 'var(--primary)',
            borderRadius: '50%',
            width: '40px',
            height: '40px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer'
          }}
          title="Daftarkan Sapi"
        >
          <Plus size={20} />
        </button>
      </div>

      {/* Live Search Form Input */}
      <div className="form-group" style={{ position: 'relative', marginBottom: '16px' }}>
        <input
          type="text"
          className="form-control"
          placeholder="Cari Nomor Tag Sapi atau Peternak..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          style={{ paddingLeft: '48px' }}
        />
        <Search
          size={18}
          style={{
            position: 'absolute',
            left: '16px',
            top: '50%',
            transform: 'translateY(-50%)',
            color: 'var(--text-muted)'
          }}
        />
      </div>

      {/* Filter Horiz Scroll Tabs */}
      <div style={{ display: 'flex', gap: '8px', overflowX: 'auto', paddingBottom: '12px', marginBottom: '16px', scrollbarWidth: 'none' }}>
        <button
          onClick={() => setSelectedFilter('all')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'all' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'all' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'all' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          Semua Sapi
        </button>
        <button
          onClick={() => setSelectedFilter('bunting')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'bunting' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'bunting' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'bunting' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          Bunting
        </button>
        <button
          onClick={() => setSelectedFilter('ib')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'ib' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'ib' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'ib' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          IB Aktif
        </button>
        <button
          onClick={() => setSelectedFilter('tidak_bunting')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'tidak_bunting' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'tidak_bunting' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'tidak_bunting' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          Tidak Bunting
        </button>
        <button
          onClick={() => setSelectedFilter('gangrep')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'gangrep' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'gangrep' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'gangrep' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          Gangrep
        </button>
        <button
          onClick={() => setSelectedFilter('kelahiran')}
          style={{
            flexShrink: 0,
            padding: '6px 12px',
            borderRadius: '20px',
            fontSize: '12px',
            fontWeight: '600',
            background: selectedFilter === 'kelahiran' ? 'var(--primary)' : 'var(--card-bg)',
            border: '1px solid',
            borderColor: selectedFilter === 'kelahiran' ? 'var(--primary)' : 'var(--card-border)',
            color: selectedFilter === 'kelahiran' ? 'white' : 'var(--text-sub)',
            cursor: 'pointer',
            transition: 'var(--transition)'
          }}
        >
          Kelahiran
        </button>
      </div>

      {/* Sapi Items List */}
      {loading ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
          <ShimmerListItem />
          <ShimmerListItem />
          <ShimmerListItem />
        </div>
      ) : filteredSapi.length > 0 ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
          {filteredSapi.map((sapi) => (
            <div
              key={sapi.id_sapi}
              className="card"
              onClick={() => viewSapiDetail(sapi.id_sapi)}
              style={{
                display: 'flex',
                gap: '16px',
                alignItems: 'center',
                cursor: 'pointer',
                transition: 'var(--transition)'
              }}
            >
              {/* Cow Photo preview */}
              <img
                src={resolvePhoto(sapi.foto_sapi)}
                alt={sapi.no_sapi}
                style={{
                  width: '64px',
                  height: '64px',
                  borderRadius: '12px',
                  objectFit: 'cover',
                  border: '1px solid rgba(255,255,255,0.06)'
                }}
                onError={(e) => {
                  (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1546445317-29f4545e6d49?w=150';
                }}
              />

              <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '8px' }}>
                  <h4 style={{ fontSize: '15px', fontWeight: '700', color: 'var(--text-main)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    <Tag size={13} style={{ color: 'var(--primary)' }} /> Tag #{sapi.no_sapi}
                  </h4>
                  <span className={getBadgeClass(sapi.status)}>
                    {getStatusLabel(sapi.status)}
                  </span>
                </div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', marginTop: '6px' }}>
                  <span style={{ fontSize: '12px', color: 'var(--text-sub)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    <User size={12} style={{ color: 'var(--text-muted)' }} /> Peternak: <strong style={{ color: 'var(--text-main)' }}>{sapi.peternak}</strong>
                  </span>
                  <span style={{ fontSize: '11px', color: 'var(--text-muted)', display: 'flex', alignItems: 'center', gap: '6px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                    <MapPin size={12} style={{ color: 'var(--text-muted)' }} /> Alamat: {sapi.alamat}
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card" style={{ padding: '40px 20px', textAlign: 'center' }}>
          <AlertCircle size={36} style={{ color: 'var(--text-muted)', marginBottom: '12px' }} />
          <h4 style={{ fontSize: '14px', color: 'var(--text-main)', fontWeight: '600' }}>Sapi Tidak Ditemukan</h4>
          <p style={{ fontSize: '12px', color: 'var(--text-sub)', marginTop: '4px' }}>
            {search ? 'Cobalah cari dengan nomor tag atau nama peternak yang berbeda.' : 'Daftarkan sapi binaan Anda melalui tombol plus.'}
          </p>
        </div>
      )}
    </div>
  );
};
