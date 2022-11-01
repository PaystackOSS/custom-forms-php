<?php
  use Illuminate\Http\Request;

  function validateRequest($request)
  {
    // parse the necessary parameters
    $method = $request->method();
    $body = $request->input();
    $path = $request->path();
    $date = $request->header("date");
    $authorization = $request->header("authorization");

    // create MD5 and SHA512 hash
    $requestBody = $method !== "GET" ? $body : "";
    $md5Hash = createMD5Hash($requestBody);
    $hmacHash = createHMACHash([$method, $path, $date, $md5Hash]);

    $authComponents = explode(" ", $authorization);

    return $hmacHash === $authComponents[1];
  }

  function createResponseHeader($response)
  {
    $date = date(DATE_ISO8601);
    $md5Hash = createMD5Hash($response);
    $hmacHash = createHMACHash([$date, $md5Hash]);

    return [
      "date" => $date,
      "authorization" => "Bearer " . $hmacHash
    ];
  }

  function createMD5Hash($json)
  {
    $jsonString = json_encode($json, JSON_UNESCAPED_SLASHES);
    return md5($jsonString);
  }

  function createHMACHash($hashParams)
  {
    return hash_hmac(
      "sha512",
      implode("\n", $hashParams),
      env('APP_SECRET') 
    );
  }
?>