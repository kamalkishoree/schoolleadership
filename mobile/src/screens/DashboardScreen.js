import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  RefreshControl,
  ActivityIndicator,
} from 'react-native';
import { api } from '../services/api';

export default function DashboardScreen({ navigation }) {
  const [dashboard, setDashboard] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadDashboard = useCallback(async () => {
    try {
      const response = await api.get('/dashboard');
      setDashboard(response.data);
    } catch (error) {
      console.error('Error loading dashboard:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    loadDashboard();
  }, [loadDashboard]);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadDashboard();
  }, [loadDashboard]);

  const handleStartChallenge = useCallback(() => {
    navigation.navigate('Challenge');
  }, [navigation]);

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#2563EB" />
      </View>
    );
  }

  if (!dashboard) {
    return (
      <View style={styles.centerContainer}>
        <Text>Error loading dashboard</Text>
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }
    >
      <View style={styles.welcomeCard}>
        <Text style={styles.welcomeText}>Welcome,</Text>
        <Text style={styles.nameText}>{dashboard.student.name}!</Text>
      </View>

      <View style={styles.statsContainer}>
        <View style={styles.statCard}>
          <Text style={styles.statLabel}>Total XP</Text>
          <Text style={styles.statValue}>{dashboard.student.total_xp}</Text>
        </View>

        <View style={styles.statCard}>
          <Text style={styles.statLabel}>Current Streak</Text>
          <Text style={styles.statValue}>{dashboard.student.current_streak} days</Text>
        </View>
      </View>

      <View style={styles.rankContainer}>
        <View style={styles.rankCard}>
          <Text style={styles.rankLabel}>Class Rank</Text>
          <Text style={styles.rankValue}>#{dashboard.rank.class_rank}</Text>
        </View>
        <View style={styles.rankCard}>
          <Text style={styles.rankLabel}>School Rank</Text>
          <Text style={styles.rankValue}>#{dashboard.rank.school_rank}</Text>
        </View>
      </View>

      {dashboard.today_challenge && !dashboard.today_challenge.has_submitted && (
        <TouchableOpacity
          style={styles.challengeButton}
          onPress={handleStartChallenge}
        >
          <Text style={styles.challengeButtonText}>
            Start Today's Challenge
          </Text>
          <Text style={styles.challengeButtonSubtext}>
            Earn {dashboard.today_challenge.xp_reward} XP
          </Text>
        </TouchableOpacity>
      )}

      {dashboard.today_challenge?.has_submitted && (
        <View style={styles.completedCard}>
          <Text style={styles.completedText}>✓ Challenge Completed</Text>
        </View>
      )}

      {!dashboard.today_challenge && (
        <View style={styles.noChallengeCard}>
          <Text style={styles.noChallengeText}>No challenge available today</Text>
        </View>
      )}
    </ScrollView>
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
  welcomeCard: {
    backgroundColor: '#2563EB',
    padding: 30,
    margin: 20,
    borderRadius: 12,
  },
  welcomeText: {
    color: '#fff',
    fontSize: 18,
    marginBottom: 5,
  },
  nameText: {
    color: '#fff',
    fontSize: 28,
    fontWeight: 'bold',
  },
  statsContainer: {
    flexDirection: 'row',
    paddingHorizontal: 20,
    marginBottom: 20,
    gap: 15,
  },
  statCard: {
    flex: 1,
    backgroundColor: '#F9FAFB',
    padding: 20,
    borderRadius: 12,
    alignItems: 'center',
  },
  statLabel: {
    fontSize: 14,
    color: '#6B7280',
    marginBottom: 8,
  },
  statValue: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#2563EB',
  },
  rankContainer: {
    flexDirection: 'row',
    paddingHorizontal: 20,
    marginBottom: 20,
    gap: 15,
  },
  rankCard: {
    flex: 1,
    backgroundColor: '#F9FAFB',
    padding: 20,
    borderRadius: 12,
    alignItems: 'center',
  },
  rankLabel: {
    fontSize: 14,
    color: '#6B7280',
    marginBottom: 8,
  },
  rankValue: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#2563EB',
  },
  challengeButton: {
    backgroundColor: '#2563EB',
    margin: 20,
    padding: 25,
    borderRadius: 12,
    alignItems: 'center',
  },
  challengeButtonText: {
    color: '#fff',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 5,
  },
  challengeButtonSubtext: {
    color: '#fff',
    fontSize: 14,
    opacity: 0.9,
  },
  completedCard: {
    backgroundColor: '#10B981',
    margin: 20,
    padding: 20,
    borderRadius: 12,
    alignItems: 'center',
  },
  completedText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: '600',
  },
  noChallengeCard: {
    backgroundColor: '#F3F4F6',
    margin: 20,
    padding: 20,
    borderRadius: 12,
    alignItems: 'center',
  },
  noChallengeText: {
    color: '#6B7280',
    fontSize: 16,
  },
});

