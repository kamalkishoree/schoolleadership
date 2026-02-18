import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  ActivityIndicator,
} from 'react-native';
import { api } from '../services/api';

export default function LeaderboardScreen() {
  const [leaderboard, setLeaderboard] = useState(null);
  const [type, setType] = useState('class');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadLeaderboard = useCallback(async () => {
    try {
      const endpoint = type === 'class' ? '/leaderboard/class' : '/leaderboard/school';
      const response = await api.get(endpoint);
      setLeaderboard(response.data.leaderboard);
    } catch (error) {
      console.error('Error loading leaderboard:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, [type]);

  useEffect(() => {
    loadLeaderboard();
  }, [loadLeaderboard]);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadLeaderboard();
  }, [loadLeaderboard]);

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#2563EB" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.toggleContainer}>
        <TouchableOpacity
          style={[styles.toggleButton, type === 'class' && styles.toggleButtonActive]}
          onPress={() => setType('class')}
        >
          <Text
            style={[
              styles.toggleText,
              type === 'class' && styles.toggleTextActive,
            ]}
          >
            Class
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.toggleButton, type === 'school' && styles.toggleButtonActive]}
          onPress={() => setType('school')}
        >
          <Text
            style={[
              styles.toggleText,
              type === 'school' && styles.toggleTextActive,
            ]}
          >
            School
          </Text>
        </TouchableOpacity>
      </View>

      <ScrollView
        style={styles.list}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
      >
        {leaderboard?.map((entry) => (
          <View
            key={entry.id}
            style={[
              styles.leaderboardItem,
              entry.rank <= 3 && styles.leaderboardItemTop,
            ]}
          >
            <View style={styles.rankContainer}>
              {entry.rank === 1 && <Text style={styles.medal}>🥇</Text>}
              {entry.rank === 2 && <Text style={styles.medal}>🥈</Text>}
              {entry.rank === 3 && <Text style={styles.medal}>🥉</Text>}
              {entry.rank > 3 && (
                <Text style={styles.rank}>{entry.rank}</Text>
              )}
            </View>
            <View style={styles.infoContainer}>
              <Text style={styles.name}>{entry.name}</Text>
              {entry.class_name && (
                <Text style={styles.className}>{entry.class_name}</Text>
              )}
            </View>
            <View style={styles.statsContainer}>
              <Text style={styles.xp}>{entry.total_xp} XP</Text>
              <Text style={styles.streak}>{entry.current_streak} 🔥</Text>
            </View>
          </View>
        ))}
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  centerContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  toggleContainer: {
    flexDirection: 'row',
    margin: 20,
    backgroundColor: '#F3F4F6',
    borderRadius: 12,
    padding: 4,
  },
  toggleButton: {
    flex: 1,
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  toggleButtonActive: {
    backgroundColor: '#2563EB',
  },
  toggleText: {
    fontSize: 16,
    color: '#6B7280',
    fontWeight: '600',
  },
  toggleTextActive: {
    color: '#fff',
  },
  list: {
    flex: 1,
  },
  leaderboardItem: {
    flexDirection: 'row',
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#E5E7EB',
    alignItems: 'center',
  },
  leaderboardItemTop: {
    backgroundColor: '#FEF3C7',
  },
  rankContainer: {
    width: 50,
    alignItems: 'center',
  },
  rank: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#6B7280',
  },
  medal: {
    fontSize: 30,
  },
  infoContainer: {
    flex: 1,
    marginLeft: 15,
  },
  name: {
    fontSize: 18,
    fontWeight: '600',
    color: '#1F2937',
    marginBottom: 4,
  },
  className: {
    fontSize: 14,
    color: '#6B7280',
  },
  statsContainer: {
    alignItems: 'flex-end',
  },
  xp: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2563EB',
    marginBottom: 4,
  },
  streak: {
    fontSize: 14,
    color: '#6B7280',
  },
});

