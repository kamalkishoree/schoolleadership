import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { api } from '../services/api';

export default function ChallengeScreen({ navigation }) {
  const [challenge, setChallenge] = useState(null);
  const [selectedOption, setSelectedOption] = useState(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  const loadChallenge = useCallback(async () => {
    try {
      const response = await api.get('/challenge/today');
      if (response.data.challenge) {
        setChallenge(response.data.challenge);
      } else if (response.data.submission) {
        Alert.alert('Already Submitted', 'You have already submitted today\'s challenge');
        navigation.goBack();
      } else {
        Alert.alert('No Challenge', 'No challenge available for today');
        navigation.goBack();
      }
    } catch (error) {
      Alert.alert('Error', 'Failed to load challenge');
      navigation.goBack();
    } finally {
      setLoading(false);
    }
  }, [navigation]);

  useEffect(() => {
    loadChallenge();
  }, [loadChallenge]);

  const handleSubmit = useCallback(async () => {
    if (!selectedOption) {
      Alert.alert('Error', 'Please select an option');
      return;
    }

    setSubmitting(true);
    try {
      const response = await api.post('/challenge/submit', {
        challenge_id: challenge.id,
        selected_option: selectedOption,
      });

      navigation.navigate('Result', {
        result: response.data,
      });
    } catch (error) {
      Alert.alert(
        'Error',
        error.response?.data?.message || 'Failed to submit challenge'
      );
    } finally {
      setSubmitting(false);
    }
  }, [selectedOption, challenge, navigation]);

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#2563EB" />
      </View>
    );
  }

  if (!challenge) {
    return null;
  }

  return (
    <ScrollView style={styles.container}>
      <View style={styles.content}>
        <Text style={styles.title}>{challenge.title}</Text>
        <Text style={styles.description}>{challenge.description}</Text>

        <View style={styles.optionsContainer}>
          <TouchableOpacity
            style={[
              styles.optionButton,
              selectedOption === 'a' && styles.optionButtonSelected,
            ]}
            onPress={() => setSelectedOption('a')}
          >
            <Text
              style={[
                styles.optionText,
                selectedOption === 'a' && styles.optionTextSelected,
              ]}
            >
              A. {challenge.option_a}
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[
              styles.optionButton,
              selectedOption === 'b' && styles.optionButtonSelected,
            ]}
            onPress={() => setSelectedOption('b')}
          >
            <Text
              style={[
                styles.optionText,
                selectedOption === 'b' && styles.optionTextSelected,
              ]}
            >
              B. {challenge.option_b}
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[
              styles.optionButton,
              selectedOption === 'c' && styles.optionButtonSelected,
            ]}
            onPress={() => setSelectedOption('c')}
          >
            <Text
              style={[
                styles.optionText,
                selectedOption === 'c' && styles.optionTextSelected,
              ]}
            >
              C. {challenge.option_c}
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[
              styles.optionButton,
              selectedOption === 'd' && styles.optionButtonSelected,
            ]}
            onPress={() => setSelectedOption('d')}
          >
            <Text
              style={[
                styles.optionText,
                selectedOption === 'd' && styles.optionTextSelected,
              ]}
            >
              D. {challenge.option_d}
            </Text>
          </TouchableOpacity>
        </View>

        <TouchableOpacity
          style={[styles.submitButton, !selectedOption && styles.submitButtonDisabled]}
          onPress={handleSubmit}
          disabled={!selectedOption || submitting}
        >
          {submitting ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.submitButtonText}>Submit</Text>
          )}
        </TouchableOpacity>
      </View>
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
  content: {
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1F2937',
    marginBottom: 15,
  },
  description: {
    fontSize: 16,
    color: '#4B5563',
    marginBottom: 30,
    lineHeight: 24,
  },
  optionsContainer: {
    marginBottom: 30,
  },
  optionButton: {
    borderWidth: 2,
    borderColor: '#E5E7EB',
    borderRadius: 12,
    padding: 20,
    marginBottom: 15,
    backgroundColor: '#F9FAFB',
  },
  optionButtonSelected: {
    borderColor: '#2563EB',
    backgroundColor: '#EFF6FF',
  },
  optionText: {
    fontSize: 16,
    color: '#1F2937',
  },
  optionTextSelected: {
    color: '#2563EB',
    fontWeight: '600',
  },
  submitButton: {
    backgroundColor: '#2563EB',
    padding: 18,
    borderRadius: 12,
    alignItems: 'center',
  },
  submitButtonDisabled: {
    backgroundColor: '#9CA3AF',
  },
  submitButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: '600',
  },
});

