import { webcrypto } from 'node:crypto';

// for web crypto api support on node.js 18 or lower
globalThis.crypto = webcrypto as Crypto;
