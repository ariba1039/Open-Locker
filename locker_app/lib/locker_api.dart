// Openapi Generator last run: : 2025-02-27T19:41:44.746956
import 'package:openapi_generator_annotations/openapi_generator_annotations.dart';

@Openapi(
  additionalProperties:
      DioProperties(pubName: 'locker_api', pubAuthor: 'Open Locker'),
  inputSpec: RemoteSpec(path: 'http://localhost/docs/api.json'),
  generatorName: Generator.dart,
  runSourceGenOnOutput: true,
  outputDirectory: 'api/locker_api',
)
class LockerApi {}