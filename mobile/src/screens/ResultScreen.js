import React, { useEffect, useRef } from 'react';
import { View, Text, TouchableOpacity, StyleSheet, Animated } from 'react-native';

export default function ResultScreen({ route, navigation }) {
  const { result } = route.params;
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const scaleAnim = useRef(new Animated.Value(0.5)).current;

  useEffect(() => {
    Animated.parallel([
      Animated.timing(fadeAnim, {
        toValue: 1,
        duration: 500,
        useNativeDriver: true,
      }),
      Animated.spring(scaleAnim, {
        toValue: 1,
        friction: 4,
        useNativeDriver: true,
      }),
    ]).start();
  }, [fadeAnim, scaleAnim]);

  const isCorrect = result.submission.is_correct;
  const xpEarned = result.submission.score;

  return (
    <View style={styles.container}>
      <Animated.View
        style={[
          styles.content,
          {
            opacity: fadeAnim,
            transform: [{ scale: scaleAnim }],
          },
        ]}
      >
        <View
          style={[
            styles.iconContainer,
            isCorrect ? styles.iconContainerSuccess : styles.iconContainerError,
          ]}
        >
          <Text style={styles.icon}>{isCorrect ? '✓' : '✗'}</Text>
        </View>

        <Text style={styles.title}>
          {isCorrect ? 'Correct Answer!' : 'Incorrect Answer'}
        </Text>

        {isCorrect && (
          <View style={styles.xpContainer}>
            <Text style={styles.xpLabel}>XP Earned</Text>
            <Text style={styles.xpValue}>+{xpEarned}</Text>
          </View>
        )}

        <View style={styles.statsContainer}>
          <View style={styles.statCard}>
            <Text style={styles.statLabel}>Total XP</Text>
            <Text style={styles.statValue}>{result.student.total_xp}</Text>
          </View>
          <View style={styles.statCard}>
            <Text style={styles.statLabel}>Streak</Text>
            <Text style={styles.statValue}>{result.student.current_streak} days</Text>
          </View>
        </View>

        <View style={styles.rankContainer}>
          <Text style={styles.rankLabel}>Your Rank</Text>
          <View style={styles.rankRow}>
            <View style={styles.rankItem}>
              <Text style={styles.rankValue}>#{result.rank.class_rank}</Text>
              <Text style={styles.rankSubtext}>Class</Text>
            </View>
            <View style={styles.rankItem}>
              <Text style={styles.rankValue}>#{result.rank.school_rank}</Text>
              <Text style={styles.rankSubtext}>School</Text>
            </View>
          </View>
        </View>

        <TouchableOpacity
          style={styles.button}
          onPress={() => navigation.navigate('Main')}
        >
          <Text style={styles.buttonText}>Back to Dashboard</Text>
        </TouchableOpacity>
      </Animated.View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    justifyContent: 'center',
    padding: 20,
  },
  content: {
    alignItems: 'center',
  },
  iconContainer: {
    width: 100,
    height: 100,
    borderRadius: 50,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 30,
  },
  iconContainerSuccess: {
    backgroundColor: '#10B981',
  },
  iconContainerError: {
    backgroundColor: '#EF4444',
  },
  icon: {
    fontSize: 50,
    color: '#fff',
    fontWeight: 'bold',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 30,
  },
  xpContainer: {
    alignItems: 'center',
    marginBottom: 30,
  },
  xpLabel: {
    fontSize: 16,
    color: '#6B7280',
    marginBottom: 10,
  },
  xpValue: {
    fontSize: 48,
    fontWeight: 'bold',
    color: '#2563EB',
  },
  statsContainer: {
    flexDirection: 'row',
    width: '100%',
    marginBottom: 30,
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
    fontSize: 24,
    fontWeight: 'bold',
    color: '#2563EB',
  },
  rankContainer: {
    width: '100%',
    backgroundColor: '#F9FAFB',
    padding: 20,
    borderRadius: 12,
    marginBottom: 30,
  },
  rankLabel: {
    fontSize: 16,
    color: '#6B7280',
    textAlign: 'center',
    marginBottom: 15,
  },
  rankRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  rankItem: {
    alignItems: 'center',
  },
  rankValue: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#2563EB',
  },
  rankSubtext: {
    fontSize: 14,
    color: '#6B7280',
    marginTop: 5,
  },
  button: {
    backgroundColor: '#2563EB',
    padding: 18,
    borderRadius: 12,
    width: '100%',
    alignItems: 'center',
  },
  buttonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: '600',
  },
});

