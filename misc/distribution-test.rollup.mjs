import typescript from '@rollup/plugin-typescript';

export default {
  input: 'src/ts/distribution-popin.ts',
  output: { file: 'misc/distribution-popin.bundle.js', format: 'es' },
  plugins: [typescript({
    tsconfig: './misc/tsconfig.distribution-test.json',
    filterRoot: false,
  })],
};
